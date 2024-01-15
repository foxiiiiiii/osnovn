window.toastifyPush = (text, type) => {
  let color = '';
  if(type == 'success') color = '#18a058';
  if(type == 'error') color = '#d03050';
  if(type == 'info') color = '#2080f0';

  Toastify({
    text,
    duration: 3000,
    close: true,
    gravity: "bottom", // `top` or `bottom`
    position: "center", // `left`, `center` or `right`
    stopOnFocus: true, // Prevents dismissing of toast on hover
    style: {
      background: color
    }
  }).showToast();
}

window.validateEmail = (email) => {
  return String(email)
    .toLowerCase()
    .match(
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
};

window.createPassword = () => {
  let upperCase = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
  let lowerCase = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
  let specialChar = ['!', '@', '#', '$', '%', '=', '&', '*', '?', '_'];
  let newPassword;

  newPassword = upperCase[Math.floor(Math.random() * upperCase.length)] + lowerCase[Math.floor(Math.random() * lowerCase.length)] + lowerCase[Math.floor(Math.random() * lowerCase.length)] + specialChar[Math.floor(Math.random() * specialChar.length)] + [Math.floor((Math.random() * 33) + 1)] + lowerCase[Math.floor(Math.random() * lowerCase.length)] + lowerCase[Math.floor(Math.random() * lowerCase.length)];


  return newPassword;
}

window.copyToClipboard = async (textToCopy) => {
    // Navigator clipboard api needs a secure context (https)
    if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(textToCopy);
    } else {
        // Use the 'out of viewport hidden text area' trick
        const textArea = document.createElement("textarea");
        textArea.value = textToCopy;
            
        // Move textarea out of the viewport so it's not visible
        textArea.style.position = "absolute";
        textArea.style.left = "-999999px";
            
        document.body.prepend(textArea);
        textArea.select();

        try {
            document.execCommand('copy');
        } catch (error) {
            console.error(error);
        } finally {
            textArea.remove();
        }
    }
}

let http = axios.create({
  baseURL: '/wp-json/tch/'
});
http.defaults.headers.token = Cookies.get('token')
window.req = http

if(typeof Cookies.get('token') === 'string') {
  store.commit('getUser');
} else {
  store.state.authed = false;
}