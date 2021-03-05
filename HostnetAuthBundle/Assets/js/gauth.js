/* global Fingerprint2 */
document.addEventListener("DOMContentLoaded", function(event) { 
  new Fingerprint2().get(function (result) {
    console.log(result)
    document.querySelector('#hash').value = result
  })
});
