
function signIn(event) {
    event.preventDefault(); 
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    
    if (email === "" || password === "") {
        alert("Please fill in both fields.");
        return;
    }
    window.location.href = 'Home.html';
}
function signUp(event) {
    event.preventDefault(); 

    
    const name = document.getElementById('name').value;

    const email = document.getElementById('newEmail').value;
    const password = document.getElementById('newPassword').value;
   

    
    if (email === "" || password === "") {
        alert("Please fill in all fields.");
        return;
    }

    
    window.location.href = 'Home.html';
}
function Instagram(){
    alert("Sorry Now we are fixing a problem in our instagram , For any info please contact us on phone number or visit us.");  
}



const form = document.getElementById('booking-form');
const errorMessage = document.getElementById('error-message');

form.addEventListener('submit', (e) => {
  e.preventDefault();
  let isValid = true;

  const name = document.getElementById('name').value.trim();
  const phone = document.getElementById('phone').value.trim();
  const people = document.getElementById('people').value.trim();
  const date = document.getElementById('date').value.trim();
  const time = document.getElementById('time').value.trim();

  if (name === '' || phone === '' || people === '' || date === '' || time === '') {
    isValid = false;
    errorMessage.textContent = 'Please fill in all fields!';
  }

  if (isValid) {
    form.submit();
  }
});


function addToCart(type, item_name, quantity, price, instructions){
    fetch("add_to_cart.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({type, item_name, quantity, price, instructions})
    })
    .then(res => res.json())
    .then(data => {
        if(data.status == "success"){
            alert("Item added to cart!");
            
        } else {
            alert("Failed to add to cart: " + data.message);
        }
    });
}


document.querySelectorAll('.add_to_cart-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const parent = btn.closest('.menu-item');
        const type = parent.dataset.type;
        const name = parent.dataset.name;
        const price = parseFloat(parent.dataset.price);
        const quantity = 1;
        const instructions = ''; 

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ type, item_name: name, quantity, price, instructions })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success'){
                alert(name + ' added to cart!');
            } else {
                alert('Failed to add to cart.');
            }
        });
    });
});
