let parameters = {
  count: false,
  letters: false,
  numbers: false,
  special: false,
};
let strengthBar = document.getElementById('strength-bar');
let msg = document.getElementById('msg');

function viewPassword() {
  var passConnexion = document.getElementById('password');

  if (passConnexion.type === 'password') {
    passConnexion.type = 'text';
  } else {
    passConnexion.type = 'password';
  }
}

function viewPasswordInscription() {
  var passInscription = document.getElementById('password');
  if (passInscription.type === 'password') {
    passInscription.type = 'text';
  } else {
    passInscription.type = 'password';
  }
}

function viewConfPasswordInscription() {
  var confPassInscription = document.getElementById('conf_Password_inscription');
  if (confPassInscription.type === 'password') {
    confPassInscription.type = 'text';
  } else {
    confPassInscription.type = 'password';
  }
}

function strengthChecker() {
  let password = document.getElementById('password').value;

  parameters.letters = /[A-Za-z]+/.test(password) ? true : false;
  parameters.numbers = /[0-9]+/.test(password) ? true : false;
  parameters.special = /[!\"$%&/()=?@~`\\.\';:+=^*_-]+/.test(password) ? true : false;
  parameters.count = password.length > 6 ? true : false;

  let barLength = Object.values(parameters).filter((value) => value);

  strengthBar.innerHTML = '';
  for (let i in barLength) {
    let span = document.createElement('span');
    span.classList.add('strength');
    strengthBar.appendChild(span);
  }

  let spanRef = document.getElementsByClassName('strength');
  for (let i = 0; i < spanRef.length; i++) {
    switch (spanRef.length - 1) {
      case 0:
        spanRef[i].style.background = '#ff3e36';
        msg.style.color = '#DA0900';
        msg.textContent = 'Votre mot de passe est très faible';
        break;
      case 1:
        spanRef[i].style.background = '#ff691f';
        msg.style.color = '#DE4A01';
        msg.textContent = 'Votre mot de passe est faible';
        break;
      case 2:
        spanRef[i].style.background = '#ffda36';
        msg.style.color = '#E2B800';
        msg.textContent = 'Votre mot de passe est bon';
        break;
      case 3:
        spanRef[i].style.background = '#0be881';
        msg.style.color = '#00BB64';
        msg.textContent = 'Votre mot de passe est fort';
        break;
    }
  }
  if (password.length == 0) {
    msg.textContent = '';
  }
}

function validateForm(nameForm) {
  let i;
  let x;
  let y = document.forms[nameForm].elements;
  console.log(y);
  for (i = 0; i < y.length; i++) {
    x = document.forms[nameForm].elements[i];
    console.log(x.nodeName);
    if (x.value === '' && x.nodeName === 'INPUT') {
      console.log('empty');
      console.log(x.nodeName);
      alert('Veuillez remplir tous les champs');
      return false;
    }
  }
}

function changeSignForm() {
  let check = document.getElementById('provider-check').checked;
  console.log(check);
  if (check == true) {
    document.getElementById('forms-company').style.display = 'none';
    document.getElementById('forms-provider').style.display = 'block';
  } else {
    document.getElementById('forms-company').style.display = 'block';
    document.getElementById('forms-provider').style.display = 'none';
  }
}

function deleteProvider(id) {
  let providerContainer = id.parentElement;
  if (providerContainer.querySelector('.selected') != null) {
    const providerId = providerContainer.querySelector('.selected').getAttribute('id');
    providerList(providerId, 'delete');
  }
  providerContainer.remove();
}

function addProvider() {
  let providerContainer = document.getElementById('provider-container');
  let newProvider = document.createElement('div');
  newProvider.classList.add('mb-4');

  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      newProvider.innerHTML = xhr.responseText;
      providerContainer.appendChild(newProvider);
    }
  };
  xhr.open('GET', 'ajaxReq/occupationDropdown.php');
  xhr.send();
}

function selectOccupation(id) {
  const selected = id.innerHTML;
  const providerContainer = id.parentElement.parentElement.parentElement;
  const provider = providerContainer.querySelector('.btn.btn-secondary.dropdown-toggle.mx-2');
  const occupation = providerContainer.querySelector('.btn.btn-secondary.dropdown-toggle');
  occupation.innerHTML = selected;
  if (provider != null) {
    const providerId = provider.getAttribute('id');
    providerList(providerId, 'delete');
    provider.remove();
  }
  let xhr = new XMLHttpRequest();
  xhr.open('GET', 'ajaxReq/providerDropdown.php?occupation=' + selected, true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      providerContainer.innerHTML += xhr.responseText;
    }
  };
  xhr.send();
}

function selectProvider(id) {
  const selected = id.innerHTML;
  const providerId = id.getAttribute('id');
  const providerContainer = id.parentElement.parentElement.parentElement;
  const occupation = providerContainer.querySelector('.btn.btn-secondary.dropdown-toggle.mx-2');
  if (providerContainer.querySelector('.selected') == null) {
    occupation.classList.add('selected');
    providerList(providerId, 'add');
  }
  occupation.innerHTML = selected;
  occupation.id = providerId;
}

function providerList(id, type) {
  const form = document.getElementById('activity-form');
  if (type == 'delete') {
    const providerInput = document.getElementById('provider' + id);
    console.log(providerInput);
    providerInput.remove();
  } else if (type == 'add') {
    const providerInput = document.createElement('input');
    providerInput.type = 'hidden';
    providerInput.name = 'provider' + id;
    providerInput.id = 'provider' + id;
    providerInput.value = id;
    form.appendChild(providerInput);
  }
}

function addMaterial() {
  const materialContainer = document.getElementById('material-container');
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      materialContainer.innerHTML += xhr.responseText;
    }
  };
  xhr.open('GET', 'ajaxReq/materialInput.php');
  xhr.send();
}

function updateMaterial(id) {
  const container = id.parentElement.parentElement;
  const material = container.querySelector('#material').value;
  const quantity = container.querySelector('#quantity').value;
  const used = container.querySelector('#used').value;

  if (quantity < used) {
    alert('La quantité disponible ne peut pas être inférieure à la quantité utilisée');
    container.querySelector('#quantity').value = parseInt(container.querySelector('#available').value) + parseInt(used);
    return;
  }

  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      console.log(xhr.responseText);
      if (xhr.responseText == 'success') {
        container.querySelector('#available').value = quantity - used;
        alert('Le matériel a bien été modifié');
      } else {
        alert("Le matériel n'a pas pu être modifié");
      }
    }
  };
  xhr.open('POST', 'verifications/verifMaterial.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('material=' + material + '&quantity=' + quantity + '&delete=false');
}

function deleteMaterial(id) {
  const container = id.parentElement.parentElement;
  const material = container.querySelector('#material').value;
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      if (xhr.responseText != 'success') {
        alert("Le matériel n'a pas pu être supprimé");
      }
    }
  };
  xhr.open('POST', 'verifications/verifMaterial.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('material=' + material + '&quantity=0&delete=true');

  container.remove();
}
