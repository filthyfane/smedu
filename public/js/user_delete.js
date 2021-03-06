const users = document.getElementById('users');

if (users) {
  users.addEventListener('click', (e) => {
    if(e.target.className === 'btn-sm btn-danger delete-user') {
      if(confirm('Ești sigur că vrei să ștergi acest utilizator din sistem?\nToate informațiile istorice și înscrierile vor fi șterse!')) {
        const id = e.target.getAttribute('data-id');
        //alert(`This is id :: ${id}`);
        fetch(`/user/delete/${id}`, {
          //credentials tag is required to avoid redirect to login page
          credentials: 'same-origin',
          method: 'DELETE'
        }).then(res => window.location.reload());
        //console.log(`Deleted id :: ${id}`);
      }
    }
  });
}
