document.addEventListener(
  "DOMContentLoaded",
  function () {
    var voteButtons = document.querySelectorAll(".orbita-vote-can-vote");

    voteButtons.forEach(function (button) {
      button.addEventListener("click", function (event) {
        upVote(button.dataset.url, button.dataset.postId, button);

        event.preventDefault;
        return false;
      });
    });

    var reportButtons = document.querySelectorAll(".orbita-report-link");

    reportButtons.forEach(function (button) {
      button.addEventListener("click", function (event) {
        report(
          button.dataset.url,
          button.dataset.commentId,
          button.dataset.postId,
          button
        );

        button.classList.add("orbita-report-link-already-reported");
        button.innerHTML = "Ok! Obrigado.";
        button.disabled = true;

        event.preventDefault;
        return false;
      });
    });
  },
  false
);

function upVote(url, postId, object) {
  // Exemplo de requisição POST
  var ajax = new XMLHttpRequest();

  // Seta tipo de requisição: Post e a URL da API
  ajax.open("POST", url, true);
  ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  // Seta paramêtros da requisição e envia a requisição
  ajax.send("action=orbita_update_post_likes&post_id=" + postId);

  // Cria um evento para receber o retorno.
  ajax.onreadystatechange = function () {
    // Caso o state seja 4 e o http.status for 200, é porque a requisiçõe deu certo.
    if (ajax.readyState == 4 && ajax.status == 200) {
      var data = ajax.responseText;

      var jsonData = JSON.parse(data);
      if (jsonData.success) {
        var textToUpdate = document.querySelector(
          "[data-votes-post-id='" + postId + "']"
        );
        textToUpdate.innerHTML = jsonData.count;

        object.classList.add("orbita-vote-already-voted");
        object.classList.remove("orbita-vote-can-vote");
      }
    }
  };
}

function report(url, commentId, postId, object) {
  // Exemplo de requisição POST
  var ajax = new XMLHttpRequest();

  // Seta tipo de requisição: Post e a URL da API
  ajax.open("POST", url, true);
  ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  // Seta paramêtros da requisição e envia a requisição
  ajax.send(
    "action=orbita_report&comment_id=" + commentId + "&post_id=" + postId
  );
}
