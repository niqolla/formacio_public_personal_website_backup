from django import forms

class uniprotIdForm(forms.Form):
    uniprot_id = forms.CharField(widget=forms.Textarea(attrs={'placeholder': 'P04247\nQ01966\nP02144\nO34167'}), label='UniProt ID')
    format_options = forms.ChoiceField(
        label='Format',
        choices=[
            ('fa', 'Fasta'),
            ('clu', 'Clustal'),
            ('msf', 'Msf'),
            ('phy', 'Phylip'),
            ('selex', 'Selex'),
            ('st', 'Stockholm'),
            ('vie', 'Vienna')
        ],
        widget=forms.Select
    )


class SequencesForm(forms.Form):
    sequences = forms.CharField(widget=forms.Textarea(\
        attrs={'placeholder': '>P04247\nMGLSDGEWQLVLNVWGKVEADLAGHGQEVLIGLFKTHPETLDKFDKFKNLKSEEDMKGS\nDLKKHGCTVLTALGTILKKKGQHAAEIQPLAQSHATKHKIPVKYLEFISEIIIEVLKKRH\nSGDFGADAQGAMSKALELFRNDIAAKYKELGFQG\n\n>P02144\nMGLSDGEWQLVLNVWGKVEADIPGHGQEVLIRLFKGHPETLEKFDKFKHLKSEDEMKASE\nDLKKHGATVLTALGGILKKKGHHEAEIKPLAQSHATKHKIPVKYLEFISECIIQVLQSKH\nPGDFGADAQGAMNKALELFRKDMASNYKELGFQG'}), \
        label='Sequnces')
    format_options = forms.ChoiceField(
        label='Format',
        choices=[
            ('fa', 'Fasta'),
            ('clu', 'Clustal'),
            ('msf', 'Msf'),
            ('phy', 'Phylip'),
            ('selex', 'Selex'),
            ('st', 'Stockholm'),
            ('vie', 'Vienna')
        ],
        widget=forms.Select
    )


class FileUploadForm(forms.Form):
    file = forms.FileField()
    format_options = forms.ChoiceField(
        label='Format',
        choices=[
            ('fa', 'Fasta'),
            ('clu', 'Clustal'),
            ('msf', 'Msf'),
            ('phy', 'Phylip'),
            ('selex', 'Selex'),
            ('st', 'Stockholm'),
            ('vie', 'Vienna')
        ],
        widget=forms.Select
    )
