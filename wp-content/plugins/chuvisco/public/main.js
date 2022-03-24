document.addEventListener(
  "DOMContentLoaded",
  function () {
    var voteButtons = document.querySelectorAll(".chuvisco-vote-can-vote");

    voteButtons.forEach(function (button) {
      button.addEventListener("click", function (event) {
        upVote(button.dataset.url, button.dataset.postId, button);

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
  ajax.send("action=chuvisco_update_post_likes&post_id=" + postId);

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

        object.classList.add("chuvisco-vote-already-voted");
        object.classList.remove("chuvisco-vote-can-vote");
      }
    }
  };
}
