from django.shortcuts import render, redirect
from django.views.decorators.csrf import csrf_protect, requires_csrf_token
from django.http import HttpResponse
from .forms import *
import subprocess
import datetime
import requests


#############################################################################
def list_of_ids_to_list_id_seq_with_API_CALL(list_of_ids):

    list_a = []
    errors_list = []

    for id in list_of_ids:

                if id != '':
                    url = f'https://rest.uniprot.org/uniprotkb/{id}.fasta'
                    response = requests.get(url)

                    if response.status_code == 200:
                        response_text_list = response.text.split('\n')
                        seq = ''

                        for elem in response_text_list:
                            if '>' not in elem:
                                seq += elem
                            else:
                                ident = elem

                        list_a.append(ident)
                        list_a.append(seq)

                    else:
                        errors_list.append(id)

    return (list_a, errors_list)
#####################################################

def result_std_out_to_dic(result_stdout):

    out_list = result_stdout.split('\n')

    ident = ''
    aln = ''
    n = 0
    dict_ident_aln = {}

    for elem in out_list:
        if '>' not in elem:
            aln += elem
        else:
            if n > 0:
                dict_ident_aln.update({ident:aln})
            n = 1
            ident = elem
            aln = ''
        dict_ident_aln.update({ident:aln})

    return dict_ident_aln
#####################################################

def dict_to_html_with_errors(get_dict, errors_list):
    html = "<html><body>"
    for i in get_dict:
        html += "<p>{}  {}</p>".format(i, get_dict[i])
    if errors_list != []:
        html += "<p>The erorrs were:</p>"
        for error in errors_list:
            html += f' {error} ;'
    html += "</body></html>"
    return html
######################################################

def result_stdout_to_html_as_clustal(result_stdout, errors_list=[]):
    out_list = result_stdout.split('\n')
    html = ''
    for i in range(0,len(out_list)):
        html += out_list[i] + '<br>'
    if errors_list != []:
        html += "<p>The erorrs were:</p>"
        for error in errors_list:
            html += f'{error}<br>'
    return html

#######################################################


def html_send_to_txt(html_send):
    output = html_send.replace('<br>', '%0A')
    output = output.replace('<p>', '%0A')
    output = output.replace('</p>', '%0A')
    return output

#######################################################


@requires_csrf_token
def success_return_html(request, input_for_clusal, errors_list=[], format='clu'):
    result = subprocess.run(["clustalo", "-i", "-", f"--outfmt={format}"], input=input_for_clusal, capture_output=True, text=True)
    html_send = result_stdout_to_html_as_clustal(result.stdout, errors_list)
    output_txt = html_send_to_txt(html_send)
    html_send = html_send.replace(' ', '&nbsp;')
    print(html_send)
    print(output_txt)
    stamp = str(datetime.datetime.now().strftime('%Y-%m-%d-%H-%M-%S'))
    context = {'html_send':html_send, 'stamp':stamp, 'output_txt':output_txt, 'format':format}
    return render(request, 'webapp/output.html', context)

@requires_csrf_token
def get_aln(request):

    form_uniprotIdForm = uniprotIdForm()
    form_SequencesForm = SequencesForm()
    form_FileUploadForm = FileUploadForm()

    if request.method == 'POST':

        if 'form_uniprotIdForm' in request.POST:
            form_uniprotIdForm = uniprotIdForm(request.POST)

            if form_uniprotIdForm.is_valid():
                list_of_ids = str(form_uniprotIdForm.cleaned_data['uniprot_id']).split('\r\n')
                (list_a, errors_list) = list_of_ids_to_list_id_seq_with_API_CALL(list_of_ids)
                sequences_fromUniprotIDs = '\n'.join(list_a)
                format = str(form_uniprotIdForm.cleaned_data['format_options'])
                print(format)
                return success_return_html(request, sequences_fromUniprotIDs, errors_list, format=format)

        if 'form_SequencesForm' in request.POST:
            form_SequencesForm = SequencesForm(request.POST)

            if form_SequencesForm.is_valid():
                all_inserted_sequences = str(form_SequencesForm.cleaned_data['sequences'])
                format = str(form_SequencesForm.cleaned_data['format_options'])
                return success_return_html(request, all_inserted_sequences, errors_list=[], format=format)

        if 'file' in request.POST:
            form_FileUploadForm = FileUploadForm(request.POST, request.FILES)
            file = request.FILES['file']

            if form_FileUploadForm.is_valid():
                file = request.FILES['file']
                format = str(form_FileUploadForm.cleaned_data['format_options'])
                content = file.read().decode('utf-8')
                return success_return_html(request, content, errors_list=[], format=format)
                
    context = {'form_uniprotIdForm': form_uniprotIdForm, 'form_SequencesForm':form_SequencesForm, 'form_FileUploadForm':form_FileUploadForm}
    return render(request, 'webapp/get_aln.html', context)

