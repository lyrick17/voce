from deep_translator import GoogleTranslator

translated = GoogleTranslator(source= 'auto', target= 'english').translate('ako ay namamatay')

print(translated)