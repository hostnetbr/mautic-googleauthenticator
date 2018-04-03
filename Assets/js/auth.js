/* global Fingerprint2 */
new Fingerprint2().get(function (result) {
  console.log(result)
  document.querySelector('#hash').value = result
})
