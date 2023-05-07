var languageFile = "";
var lang = {};
var currentIndex = 0;
var learned = [];
var currentWord = "";
var currentMode = "";
var currentLearned = false;

$(document).ready(function() {
  // Bind events to buttons
  $("#start-button").click(startTraining);
  $("#show-answer-button").click(showAnswer);
  $("#learned-button").click(markLearned);
});

function startTraining() {
  // Initialize variables
  currentIndex = 0;
  learned = [];
  currentMode = $("input[name=mode]:checked").val();
  currentLearned = false;
  loadLanguageFile();
}

function loadLanguageFile() {
  // Load language file
  var fileName = $("#language-select").val();
  $.get("lang/" + fileName, function(data) {
    languageFile = data;
    parseLanguageFile();
    showNextWord();
  });
}

function parseLanguageFile() {
  // Parse language file and populate language object
  var lines = languageFile.split("\n");
  lang = {};
  for (var i = 1; i < lines.length; i++) {
    var cols = lines[i].split(";");
    if (cols.length >= 2) {
      lang[cols[0].trim()] = cols[1].trim();
    }
  }
}

function showNextWord() {
  // Get next word
  if (currentMode == "original") {
    currentWord = Object.keys(lang)[currentIndex];
  } else if (currentMode == "foreign") {
    currentWord = lang[Object.keys(lang)[currentIndex]];
  } else {
    var randomIndex = Math.floor(Math.random() * Object.keys(lang).length);
    currentWord = Object.keys(lang)[randomIndex];
  }
  $("#word-display").html(currentWord);
}

function showAnswer() {
  // Show answer
  var translation;
  if (currentMode == "original") {
    translation = lang[currentWord];
  } else {
    translation = currentWord;
    currentWord = Object.keys(lang).find(key => lang[key] === currentWord);
  }
  $("#word-display").html(translation);
  currentLearned = false;
}

function markLearned() {
  // Mark word as learned and show next word
  if (!currentLearned) {
    learned.push(currentWord);
  }
  if (learned.length >= Object.keys(lang).length) {
    alert("Congratulations! You have learned all the words in this language file.");
    return;
  }
  currentLearned = true;
  currentIndex++;
  showNextWord();
}

function logout() {
  // Redirect to logout page
  window.location.href = "logout.php";
}

