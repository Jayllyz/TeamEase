<tr>
    <td>
        <input type="text" class="form-control" id="name" placeholder="Nom">
    </td>
    <td>
        <input type="number" class="form-control" id="quantity" placeholder="Quantité">
    </td>
    <td>
        <input type="number" class="form-control" id="used" value="0" disabled>
    </td>
    <td>
        <input type="number" class="form-control" id="available" value="0" disabled>
    </td>
    <td>
        <button type="button" class="btn btn-primary" onclick="updateMaterial()">Modifer</button>
        <button type="button" class="btn btn-danger" onclick="deleteMaterial()">Supprimer</button>
    </td>
</tr>