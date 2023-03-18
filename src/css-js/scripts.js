let parameters = {
  count: false,
  letters: false,
  numbers: false,
  special: false,
};

function viewPassword() {
  var passConnexion = document.getElementById('password');

  if (passConnexion.type === 'password') {
    passConnexion.type = 'text';
  } else {
    passConnexion.type = 'password';
  }
}

function viewPasswordInscription(id) {
  var passInscription = id.parentElement.querySelector('#password');
  if (passInscription == null) {
    passInscription = id.parentElement.querySelector('#passwordProvider');
  }

  if (passInscription.type === 'password') {
    passInscription.type = 'text';
  } else {
    passInscription.type = 'password';
  }
}

function viewConfPasswordInscription(id) {
  var confPassInscription = id.parentElement.querySelector('#conf_Password_inscription');
  if (confPassInscription.type === 'password') {
    confPassInscription.type = 'text';
  } else {
    confPassInscription.type = 'password';
  }
}

function strengthChecker(id) {
  let password;
  let strengthBar;
  let msg;
  if (id.id == 'password') {
    password = id.parentElement.querySelector('#password').value;
    strengthBar = document.getElementById('strength-bar');
    msg = document.getElementById('msg');
  } else {
    password = id.parentElement.querySelector('#passwordProvider').value;
    strengthBar = document.getElementById('strength-bar-provider');
    msg = document.getElementById('msg-provider');
  }
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
  for (i = 0; i < y.length; i++) {
    x = document.forms[nameForm].elements[i];
    if (x.value === '' && x.nodeName === 'INPUT') {
      alert('Veuillez remplir tous les champs');
      return false;
    }
  }
}

function changeSignForm() {
  let check = document.getElementById('provider-check').checked;
  if (check == true) {
    document.getElementById('forms-company').style.display = 'none';
    document.getElementById('forms-provider').style.display = 'block';
  } else {
    document.getElementById('forms-company').style.display = 'block';
    document.getElementById('forms-provider').style.display = 'none';
  }
}

function checkRadio(id) {
  let checker = document.getElementById(id).innerText;
  let companyCheck = document.getElementById('radioCompany');
  let providerCheck = document.getElementById('provider-check');
  if (checker == 'company') {
    providerCheck.checked = false;
    companyCheck.checked = true;
    document.getElementById('table-company').style.display = 'none';
    document.getElementById('table-provider').style.display = 'block';
  }
  if (checker == 'provider') {
    companyCheck.checked = false;
    providerCheck.checked = true;
    document.getElementById('table-company').style.display = 'none';
    document.getElementById('table-provider').style.display = 'block';
  }
}

function changeTable() {
  let check = document.getElementById('provider-check').checked;
  if (check == true) {
    document.getElementById('table-company').style.display = 'none';
    document.getElementById('table-provider').style.display = 'block';
  } else {
    document.getElementById('table-company').style.display = 'block';
    document.getElementById('table-provider').style.display = 'none';
  }
}

function unassignProvider(id) {
  let providerContainer = id.parentElement;
  if (providerContainer.querySelector('.selected') != null) {
    const providerId = providerContainer.querySelector('.selected').getAttribute('id');
    providerList(providerId, 'delete');
  }
  providerContainer.remove();
}

function assignProvider() {
  const providerContainer = document.getElementById('provider-container');
  const newProvider = document.createElement('div');
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

function addMaterial(element) {
  const body = element.parentElement.parentElement.querySelector('tbody');
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      body.innerHTML += xhr.responseText;
    }
  };
  xhr.open('POST', 'ajaxReq/materialInput.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('type=stock');
}

function updateMaterial(element, idMaterial) {
  const row = element.parentElement.parentElement;
  const name = row.querySelector('#name').value;
  const quantity = row.querySelector('#quantity').value;
  const used = row.querySelector('#used').value;

  if (parseInt(quantity) < parseInt(used)) {
    alert('La quantité disponible ne peut pas être inférieure à la quantité utilisée');
    row.querySelector('#quantity').value = parseInt(row.querySelector('#available').value) + parseInt(used);
    return;
  }

  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      if (xhr.responseText == 'success') {
        alert('Le matériel à bien été modifié');
        row.querySelector('#available').value = quantity - used;
      } else {
        alert("Le matériel n'a pas pu être modifié");
      }
    }
  };
  xhr.open('POST', 'verifications/verifMaterial.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('id=' + idMaterial + '&material=' + name + '&quantity=' + quantity);
}

function deleteMaterial(element, idMaterial) {
  if (!confirm('Voulez-vous vraiment supprimer ce matériel ?')) {
    return;
  }

  const row = element.parentElement.parentElement;
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      if (xhr.responseText == 'success') {
        alert('Le matériel à bien été supprimé');
        row.remove();
      } else {
        alert("Le matériel n'a pas pu être supprimé");
      }
    }
  };
  xhr.open('POST', 'verifications/verifMaterial.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('id=' + idMaterial + '&delete=true');
}

function allocateMaterial(element) {
  const body = element.parentElement.parentElement.querySelector('tbody');
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      body.innerHTML += xhr.responseText;
    }
  };
  xhr.open('POST', 'ajaxReq/materialInput.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('type=location');
}

function selectMaterialForAllocation(element, id) {
  const button = element.parentElement.parentElement.parentElement.querySelector('.btn');
  button.innerHTML = element.innerHTML;
  button.id = id;
}

function updateAllocatedMaterial(element) {
  const row = element.parentElement.parentElement;
  const id = row.querySelector('th').querySelector('button').id;
  const quantity = row.querySelector('#quantity').value;

  let type;
  let idPosition = -1;
  const nature = row.parentElement.parentElement.parentElement;
  if (nature.classList.contains('location')) {
    type = 'location';
    idPosition = window.location.search;
    idPosition = idPosition.split('=')[1];
    console.log(idPosition);
  } else if (nature.classList.contains('room')) {
    type = 'room';
    idPosition = row.parentElement.parentElement.parentElement.id;
  }

  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      if (xhr.responseText == 'success') {
        alert('Le matériel à bien été modifié');
      } else {
        alert("Le matériel n'a pas pu être modifié");
      }
    }
  };
  xhr.open('POST', 'verifications/verifMaterial.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('id=' + id + '&quantity=' + quantity + '&type=' + type + '&idPosition=' + idPosition);
}

function unallocateMaterial(element) {
  row = element.parentElement.parentElement;
  const id = row.querySelector('th').querySelector('button').id;

  const nature = row.parentElement.parentElement.parentElement;
  if (nature.classList.contains('location')) {
    type = 'location';
    idPosition = window.location.search;
    idPosition = idPosition.split('=')[1];
  } else if (nature.classList.contains('room')) {
    type = 'room';
    idPosition = row.parentElement.parentElement.parentElement.id;
  }

  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      if (xhr.responseText == 'success') {
        alert('Le matériel à bien été supprimé');
        row.remove();
      } else {
        alert("Le matériel n'a pas pu être supprimé");
      }
    }
  };
  xhr.open('POST', 'verifications/verifMaterial.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('id=' + id + '&type=' + type + '&idPosition=' + idPosition + '&delete=true');
}

function assignMaterial() {
  const materialContainer = document.getElementById('material-container');
  const newMaterial = document.createElement('div');
  newMaterial.classList.add('mb-4');

  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      const newMaterial = document.createElement('div');
      newMaterial.classList.add('mb-4');
      newMaterial.innerHTML = xhr.responseText;
      materialContainer.appendChild(newMaterial);
    }
  };
  xhr.open('GET', 'ajaxReq/materialDropdown.php');
  xhr.send();
}

function selectMaterial(id) {
  const selected = id.innerHTML;
  const materialId = id.getAttribute('id');
  const materialContainer = id.parentElement.parentElement.parentElement;
  const material = materialContainer.querySelector('.btn.btn-secondary.dropdown-toggle');
  material.innerHTML = selected;

  if (materialContainer.querySelector('.selected') == null) {
    material.classList.add('selected');
    const materialInput = document.createElement('input');
    materialInput.type = 'number';
    materialInput.id = materialId;
    material.id = materialId;
    materialInput.setAttribute('onchange', 'quantityChange(this.value, this.id);');
    materialInput.classList.add('form-control');
    const input = materialContainer.querySelector('.inputNumber');
    input.appendChild(materialInput);
    materialList(materialId, 'add', 'material');
  } else {
    const oldId = materialContainer.querySelector('.selected').getAttribute('id');
    materialContainer.querySelector('input').value = '';
    materialList(oldId, 'delete', 'material');
    materialList(materialId, 'add', 'material');
    materialList(oldId, 'delete', 'quantity');
    materialList(materialId, 'add', 'quantity');
    material.id = materialId;
    materialContainer.querySelector('input').id = materialId;
  }
}

function quantityChange(value, id) {
  if (value < 0) {
    alert('La quantité ne peut pas être négative');
    document.getElementById(id).parentElement.querySelector('.form-control').value = '';
    return;
  }
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      if (xhr.responseText < value) {
        alert('La quantité de matériel disponible est insuffisante, la quantité disponible:' + xhr.responseText);
        document.getElementById(id).parentElement.querySelector('.form-control').value = '';
        return;
      }
    }
  };
  xhr.open('POST', 'verifications/verifMaterialStock.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('id=maximum');
  if (document.getElementById('quantity' + id)) {
    materialList(id, 'delete', 'quantity');
  }
  materialList(id, 'add', 'quantity', value);
}

function unassignMaterial(id) {
  const materialContainer = id.parentElement;
  if (materialContainer.querySelector('.selected') != null) {
    const materialId = materialContainer.querySelector('.selected').getAttribute('id');
    materialList(materialId, 'delete', 'material');
    if (document.getElementById('quantity' + materialId)) {
      materialList(materialId, 'delete', 'quantity');
    }
  }
  materialContainer.remove();
}

function materialList(id, type, element, quantity) {
  const form = document.getElementById('activity-form');
  if (type == 'add') {
    if (element == 'material') {
      const materialInput = document.createElement('input');
      materialInput.type = 'hidden';
      materialInput.name = 'material' + id;
      materialInput.id = 'material' + id;
      materialInput.value = id;
      form.appendChild(materialInput);
    } else if (element == 'quantity') {
      const materialInput = document.createElement('input');
      materialInput.type = 'hidden';
      materialInput.name = 'quantity' + id;
      materialInput.id = 'quantity' + id;
      materialInput.value = quantity;
      form.appendChild(materialInput);
    }
  } else if (type == 'delete') {
    if (element == 'material') {
      const materialInput = document.getElementById('material' + id);
      materialInput.remove();
    } else if (element == 'quantity') {
      const materialInput = document.getElementById('quantity' + id);
      materialInput.remove();
    }
  }
}

function checkConfirm(text) {
  if (confirm(text) === true) {
    return true;
  } else {
    return false;
  }
}

function populateActivity(page) {
  let search = localStorage.getItem('search');
  if (search == null) {
    search = 'none';
  }
  if (search == 'maxAttendeeDesc') {
    const element = document.getElementById('maxAttendee');
    element.classList.add('desc');
    element.innerHTML = 'Nombre de participants <i class="bi bi-arrow-down-short"></i>';
  }
  const activities = document.getElementById('activities');
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      activities.innerHTML = xhr.responseText;
    }
  };
  xhr.open('POST', 'ajaxReq/populateActivities.php?page=' + page);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('search=' + search + '&searchBarInput=' + searchBarInput);

  window.addEventListener('beforeunload', function () {
    localStorage.removeItem('search');
  });
}

function removeFilter(element) {
  if (!element.classList.contains('desc') || !element.classList.contains('asc')) {
    const nameElement = document.getElementById('name');
    nameElement.innerHTML = 'Nom';
    const maxAttendeeElement = document.getElementById('maxAttendee');
    maxAttendeeElement.innerHTML = 'Nombre de participants';
    const durationElement = document.getElementById('duration');
    durationElement.innerHTML = 'Durée';
    const priceElement = document.getElementById('price');
    priceElement.innerHTML = 'Prix';
    const statusElement = document.getElementById('status');
    if (statusElement != null) {
      statusElement.innerHTML = 'Statut';
    }
  }
  const filter = element.parentElement;
  let selectedFilter = filter.querySelector('.asc');
  if (selectedFilter != null) {
    selectedFilter.classList.remove('asc');
  }
  selectedFilter = filter.querySelector('.desc');
  if (selectedFilter != null) {
    selectedFilter.classList.remove('desc');
  }
}

function filterMaxAttendee(page, element) {
  if (element.classList.contains('desc')) {
    removeFilter(element);
    element.classList.add('asc');
    element.innerHTML = 'Nombre de participants <i class="bi bi-arrow-up-short"></i>';
    localStorage.setItem('search', 'maxAttendeeAsc');
  } else if (element.classList.contains('asc')) {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Nombre de participants <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'maxAttendeeDesc');
  } else {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Nombre de participants <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'maxAttendeeDesc');
  }
  populateActivity(page);
}

function filterName(page, element) {
  if (element.classList.contains('desc')) {
    removeFilter(element);
    element.classList.add('asc');
    element.innerHTML = 'Nom <i class="bi bi-arrow-up-short"></i>';
    localStorage.setItem('search', 'nameAsc');
  } else if (element.classList.contains('asc')) {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Nom <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'nameDesc');
  } else {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Nom <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'nameDesc');
  }
  populateActivity(page);
}

function filterDuration(page, element) {
  if (element.classList.contains('desc')) {
    removeFilter(element);
    element.classList.add('asc');
    element.innerHTML = 'Durée <i class="bi bi-arrow-up-short"></i>';
    localStorage.setItem('search', 'durationAsc');
  } else if (element.classList.contains('asc')) {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Durée <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'durationDesc');
  } else {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Durée <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'durationDesc');
  }
  populateActivity(page);
}

function filterPrice(page, element) {
  if (element.classList.contains('desc')) {
    removeFilter(element);
    element.classList.add('asc');
    element.innerHTML = 'Prix <i class="bi bi-arrow-up-short"></i>';
    localStorage.setItem('search', 'priceAsc');
  } else if (element.classList.contains('asc')) {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Prix <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'priceDesc');
  } else {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Prix <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'priceDesc');
  }
  populateActivity(page);
}

function filterStatus(page, element) {
  if (element.classList.contains('desc')) {
    removeFilter(element);
    element.classList.add('asc');
    element.innerHTML = 'Statut <i class="bi bi-arrow-up-short"></i>';
    localStorage.setItem('search', 'statusAsc');
  } else if (element.classList.contains('asc')) {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Statut <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'statusDesc');
  } else {
    removeFilter(element);
    element.classList.add('desc');
    element.innerHTML = 'Statut <i class="bi bi-arrow-down-short"></i>';
    localStorage.setItem('search', 'statusAsc');
  }
  populateActivity(page);
}

function selectedDay(day) {
  if (document.getElementById(day + 'Hour').style.display == 'block') {
    document.getElementById(day + 'Hour').style = 'display: none';
  } else {
    document.getElementById(day + 'Hour').style = 'display: block';
  }
}

function addLocation(element) {
  nameLocation = element.parentElement.parentElement.querySelector('.name').value;
  addressLocation = element.parentElement.parentElement.querySelector('.address').value;

  if (nameLocation != '' && addressLocation != '') {
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
        if (this.responseText == 'success') {
          visuallyAddLocation(nameLocation, addressLocation);
          alert('Le site a bien été ajouté');
        } else {
          alert(this.responseText);
        }
      }
    };
    xhr.open('POST', 'verifications/verifLocation.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('name=' + nameLocation + '&address=' + addressLocation);
  } else {
    alert('Veuillez remplir tous les champs');
  }
}

function visuallyAddLocation(name, address) {
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('locationContainer').innerHTML += this.responseText;
    }
  };
  xhr.open('POST', 'ajaxReq/locationAccordion.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('name=' + name + '&address=' + address);
}

function deleteLocation(element, id) {
  const location = element.parentElement.parentElement.parentElement;
  if (!confirm('Voulez-vous vraiment supprimer ce site ?')) {
    return;
  }
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == 'success') {
        alert('Le site a bien été supprimé');
      } else {
        alert('Une erreur est survenue');
      }
    }
  };
  xhr.open('POST', 'verifications/verifLocation.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('delete=' + id);
  location.remove();
}

function populateLocation() {
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('locationContainer').innerHTML = this.responseText;
    }
  };
  xhr.open('POST', 'ajaxReq/locationAccordion.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('populate=true');
}

function addRoom(element, id) {
  const body = element.parentElement.parentElement.querySelector('.accordion-body');
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText.includes('success')) {
        let roomId = this.responseText.split('success')[1];

        xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            body.innerHTML += this.responseText;
          }
        };

        xhr.open('POST', 'ajaxReq/locationAccordion.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('room=true&id=' + roomId);
      } else {
        alert(this.responseText);
        return;
      }
    }
  };
  xhr.open('POST', 'verifications/verifRoom.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('add=true&location=' + id);
}

function updateRoom(element, id) {
  let name = element.parentElement.parentElement.querySelector('input').value;
  if (name == '') {
    alert('Veuillez nommer la salle');
    return;
  }
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == 'success') {
        alert('La salle a bien été modifiée');
      } else {
        alert(this.responseText);
      }
    }
  };
  xhr.open('POST', 'verifications/verifRoom.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('id=' + id + '&name=' + name);
}

function deleteRoom(element, id) {
  if (!confirm('Voulez-vous vraiment supprimer cette salle ?')) {
    return;
  }
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == 'success') {
        alert('La salle a bien été supprimée');
        element.parentElement.parentElement.remove();
      } else {
        alert(this.responseText);
      }
    }
  };
  xhr.open('POST', 'verifications/verifRoom.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('id=' + id + '&delete=true');
}
