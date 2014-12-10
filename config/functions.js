// Delete confirm
function confirmDelete(delUrl) {
  if (confirm("Opravdu chcete odstranit tento příspěvěk?")) {
    document.location = delUrl;
  }
}