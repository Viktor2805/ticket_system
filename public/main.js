// get properties of tickets and embed as form in file index.html
$.get("index.php", function(data ){
  JSON.parse(data).map(prop => {
    $("#form_check").append(
      `<input id="ticket" type="checkbox" name="${prop}"/>`+
      `<label for="${prop}">${prop}</label><br>`)
  })
  
  $("#form_check").
  append(`<button type="submit" form="form_check" value="Submit">Get tickets</button>`)
});

// Creating array for saving tickets props
var checkedTicketsProps = [];  
$('#form_check').on('click', function(e) {
    if (e.target.id === "ticket"  && e.target.checked) {
      let ticketProp = e.target.name;
      checkedTicketsProps.push(ticketProp);
    }
});

// Post checkedTicketsProps
$( "#form_check" ).on( "submit", function(e) {
  $("#csv").remove();
  $.ajax({
    type: "POST",
    url: "index.php",
    data:{checkedTicketsProps:{...checkedTicketsProps}},
  }).done(function() {
    $("body").
      append(`<a id="csv" href="./headers.csv">Download csv file</a>`)
  });

    e.preventDefault();
})

