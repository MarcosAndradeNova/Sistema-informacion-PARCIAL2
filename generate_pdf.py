import os
import glob
from fpdf import FPDF

md_files = glob.glob(r'public\bancos_preguntas\*.md')

class PDF(FPDF):
    def header(self):
        pass

    def footer(self):
        pass

for md_file in md_files:
    pdf_file = md_file.replace('.md', '.pdf')
    print(f"Processing {md_file} -> {pdf_file}")
    
    pdf = PDF()
    pdf.add_page()
    pdf.set_auto_page_break(auto=True, margin=15)

    try:
        with open(md_file, 'r', encoding='utf-8') as f:
            lines = f.readlines()
            
        for line in lines:
            line = line.strip()
            if not line:
                pdf.ln(5)
                continue
                
            # Encoding workaround for latin-1 since fpdf doesn't natively support utf-8 without true type fonts
            text = line.encode('latin-1', 'replace').decode('latin-1')
            
            if line.startswith('#'):
                pdf.set_font('Arial', 'B', 14)
                pdf.multi_cell(0, 8, text.replace('#', '').strip())
                pdf.ln(2)
            else:
                pdf.set_font('Arial', '', 11)
                pdf.multi_cell(0, 6, text)

        pdf.output(pdf_file)
        print(f"PDF successfully generated: {pdf_file}")
    except Exception as e:
        print(f"Error processing {md_file}: {e}")
