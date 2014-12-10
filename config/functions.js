// Delete confirm
function confirmDelete(delUrl) {
  if (confirm("Opravdu chcete odstranit tento příspěvěk?")) {
    document.location = delUrl;
  }
}

// Add prestup confirm
function confirmPrestup() {
  if (confirm("Opravdu chcete přidat přestup? Tuto akci již nebude možné vrátit zpět.")) {
  }
}