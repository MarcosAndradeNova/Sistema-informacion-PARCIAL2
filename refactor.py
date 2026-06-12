import os
import re

directories = [
    'app/Http/Controllers',
    'app/Models',
    'resources/views',
    'routes'
]

replacements = {
    'ci_usuario': 'ciusuario',
    'apellido_pat': 'apellidopat',
    'apellido_mat': 'apellidomat',
    'estado_admision': 'estadodocum',
    'cod_postulacion': 'codpost',
    'id_pago': 'idpago',
    'numero_recibo': 'numerorecibo',
    'metodo_pago': 'metodopago',
    "'DOCUMENTOS_PENDIENTES'": "'PENDIENTE'",
    "'DOCUMENTOS_RECHAZADOS'": "'RECHAZADO'",
    "'DOCUMENTOS_VERIFICADOS'": "'VERIFICADO'",
    "'PAGO_PENDIENTE'": "'APROBADO'",
    "'PAGO_CONFIRMADO'": "'INSCRITO'",
    "'POSTULANTE_ACTIVO'": "'INSCRITO'"
}

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content

    for old, new in replacements.items():
        content = content.replace(old, new)

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated: {filepath}")

def main():
    for d in directories:
        if not os.path.exists(d):
            continue
        for root, _, files in os.walk(d):
            for file in files:
                if file.endswith('.php') or file.endswith('.blade.php'):
                    process_file(os.path.join(root, file))

if __name__ == '__main__':
    main()
