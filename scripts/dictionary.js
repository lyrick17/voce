const wordSpans = document.querySelectorAll(".word-span");
const dictDiv = document.querySelector(".dict-div");
const displayWord = document.querySelector(".hovered-word");
const displayedMeaning = document.querySelector(".word-meaning");



const nonLetterRegex = /[^a-zA-Z'-]/g;
wordSpans.forEach((wordspan) => {
  wordspan.addEventListener("click", () => {
    
    // Displays the word on the dictionary div 
    displayedMeaning.textContent = "Loading";
    let clickedWord = wordspan.textContent;
    clickedWord = clickedWord.replace(nonLetterRegex, "")
    displayWord.textContent = clickedWord;
    displayMeaning(clickedWord);
  });
});


async function displayMeaning(word){
    const data = new FormData();
    data.append("word", word);

    await fetch('utilities/getmeaning.php', {
        method : 'POST',
        body: data
    }).then((res) => res.json())
    .then((response) => {


        console.log(response);
        displayedMeaning.textContent = "";

        Object.keys(response).forEach(key => {
            
            displayedMeaning.innerHTML = displayedMeaning.innerHTML + String(key) + ": <br>";

            for(let i = 0; i < response[key].length; i++){
                displayedMeaning.innerHTML = displayedMeaning.innerHTML + (i+1) + ".) " + response[key][i] + "<br>";
            }

            displayedMeaning.innerHTML = displayedMeaning.innerHTML + "<br>";
        });



    });
    
}




