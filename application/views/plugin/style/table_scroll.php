<style>
table.scroll {
    /* width: 100%; */ /* Optional */
    /* border-collapse: collapse; */
    border-spacing: 0;
    border: 2px solid black;
}

table.scroll tbody,
table.scroll thead { display: block; }

table.scroll tbody {
    height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
}

table.scroll tbody { border-top: 2px solid black; }

table.scroll tbody td:last-child, table.scroll thead th:last-child {
    border-right: none;
}
</style>

<script type="text/javascript">
// Change the selector if needed
var $table = $('table.scroll'),
  $bodyCells = $table.find('tbody tr:first').children(),
  colWidth;

// Adjust the width of thead cells when window resizes
$(window).resize(function() {
  // Get the tbody columns width array
  colWidth = $bodyCells.map(function() {
      return $(this).width();
  }).get();

  // Set the width of thead columns
  $table.find('thead tr').children().each(function(i, v) {
      $(v).width(colWidth[i]);
  });
}).resize(); // Trigger resize handler
</script>
