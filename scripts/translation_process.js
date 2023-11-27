// provides updates on user while translation is happening
//  using fetch api

fetch("../audio_translate.php")
    .then((response) => {
        console.log('resolved', response);
    })
    .catch((err) => {
        console.log('rejected', err);
    });