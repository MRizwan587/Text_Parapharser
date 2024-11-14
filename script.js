document.getElementById('paraphraseForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Get the input values
    let inputText = document.getElementById('inputText').value;
    let tone = document.getElementById('tone').value;
    let skipRealtime = document.getElementById('skipRealtime').checked ? 1 : 0;

    // Display "Processing..." message
    document.getElementById('outputText').innerText = "Processing... Please wait.";

    // Send POST request to PHP file
    fetch('check.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            string: inputText,
            tone: tone,
            skipRealtime: skipRealtime
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('outputText').innerHTML = "Paraphrased Text: " + data.message;
        } else {
            document.getElementById('outputText').innerHTML = "Error paraphrasing text: " + data.message;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('outputText').innerHTML = "Error: An unexpected error occurred.";
    });
});
