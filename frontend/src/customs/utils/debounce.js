export function debounce (fn, delay){
  var timeoutID = null
  return function (){
    var args = arguments
    timeoutID = setTimeout(function(){
      fn.apply(this, args)
    }, delay)
  }
}