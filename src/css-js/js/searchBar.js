function searchBar(search) {
  let url = 'ajaxReq/suggestion.php';

  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      const suggestions = document.getElementById('suggestions');
      suggestions.innerHTML = xhr.responseText;
    }
  };
  xhr.open('POST', url);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('search=' + search);
}
