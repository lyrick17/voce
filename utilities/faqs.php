<?php
// consists of all the questions and answers in faqs
    $faq_question = array();
    $faq_answer = array();

    array_push($faq_question, "What does Voce Do?");
    array_push($faq_answer, "Voce allows your text inputs to be translated in another language in the Text to Text section. Also, our main feature
                            allows you to upload your audio file, and it will be translated into another language!");

    array_push($faq_question, "How do I translate my audio?");
    array_push($faq_answer, "Go to Audio to Text, then select a model, the language of your audio in source language,
                            and the language you want it to be translated in target language. Upload your audio file, and click translate. 
                            Then, wait for the system to process your request.");

    array_push($faq_question, "Can Voce remove backgound music in my audio?");
    array_push($faq_answer, "Yes! Simply click the checkbox under the upload file to remove the background music. Please take note that 
                            the background music will not be removed if you did not click the checkbox; thus, the system might not
                            be able to handle the audio well.");
    
    array_push($faq_question, "Why is my translation not accurate?");
    array_push($faq_answer, "The accuracy of the translation depends on the \"model size\" of your choice.
                            \nChoose a lighter model, like 'Base' or 'Small' to have a quicker translation but it can be less accurate. 
                            \nChoose a heavier model, like 'Medium' or 'Large' to have a more accurate translation, but it would take more time than lighter models.");

    array_push($faq_question, "Are my translations saved?");
    array_push($faq_answer, "Yes! All of your translations are saved and you can go back and see them again! 
                           In Text to Text section or Audio to Text section, scroll down to see the table that contains your translations from recent to the oldest");
?>