import os
import glob

files = glob.glob('resources/views/operator/**/*.blade.php', recursive=True)
files.append('resources/views/layouts/app.blade.php')

replacements = {
    'blue-800': 'blue-950',
    'blue-700': 'blue-900',
    'blue-600': 'blue-800',
    'blue-500': 'blue-700',
    'blue-400': 'blue-600',
    'blue-300': 'blue-500',
    'blue-200': 'blue-400',
    'blue-100': 'blue-200',
    'blue-50': 'blue-100'
}

count = 0
for f in files:
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
    
    new_content = content
    for old, new in replacements.items():
        new_content = new_content.replace(old, new)
        
    if new_content != content:
        with open(f, 'w', encoding='utf-8') as file:
            file.write(new_content)
        count += 1

print(f'Replaced in {count} files.')
