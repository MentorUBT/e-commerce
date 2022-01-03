const form = document.getElementById('form');
const firstName = document.getElementById('fname');
const lastName = document.getElementById('lname');
const email = document.getElementById('email');
const password = document.getElementById('password');
const errorElement = document.getElementById('error');

form.addEventListener('submit', (e) => {
  let messages = []
  if (fname.value === '' || fname.value == null) {
    messages.push('First name is required!')
  }

  if (lname.value === '' || lname.value == null) {
    messages.push('Last name is required!')
  }

  if (email.value === '' || email.value == null) {
    messages.push('Email address is required!')
  }

  if (!email.contains === '!') {
    messages.push('Email must contain an "@" symbol!')
  }

  if (password.value.length <= 6) {
    messages.push('Password must be longer than 6 characters!')
  }

  if (password.value.length >= 20) {
    messages.push('Password must be less than 20 characters!')
  }

  if (password.value === 'password') {
    messages.push('Password cannot be "password"!');
  }

  if (messages.length > 0) {
    e.preventDefault();
    errorElement.innerText = messages.join('\n'); 
  }
});