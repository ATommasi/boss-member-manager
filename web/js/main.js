
/* custom filtering function */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseInt( $('#mindays').val(), 10 );
        var age = parseInt( data[4] ) || 0; // use data for the age column

        if (  isNaN( min ) || age >= min )
        {
            return true;
        }
        return false;
    }
);


$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
$(document).ready(function(){







       var UserTable = $('#userTable').DataTable({
              "order": [[ 3, 'desc' ], [1, 'asc' ]],
              "sAjaxDataProp": "",
              //"serverSide": true,
              "deferRender": true,
              "PaginationType": "bootstrap",
              "ajax": {
                     "url": "/usersajax.php?method=getByTag&tag=1284452",
                     "type": "GET"
              },
              "columns": [
                     { "data": "action", "orderable": false, "searchable": false},
                     { "data": "accountid" },
                     { "data": "username", "type": "html" },
                     { "data": "joinDate" },
                     { "data": "days_old", "type": "num" },
                     { "data": "ts3_user", "width": "32px", "orderable": false },
                     { "data": "user_id" }
              ],
              "columnDefs": [{
                     "targets": 0, // action menu column
                     "width": "80px"

              }, {
                     "targets": 1,
                     "data": "ccount",
                     "render": function ( data, type, row, meta ) {
                            //console.log(meta);
                             return (data ||'')+'&nbsp;<span rowid="'+ meta.row+'" user_id="'+row.user_id+'" data="'+data+'" class="userediticon hidden "><i class = "glyphicon-pencil glyphicon" id="edit" style="color:#ccc" onmouseover="this.style.color=\'#32a5e8\'" onmouseout="this.style.color=\'#ccc\'"></i></span>';

                     }

              }, {

                     "targets": 2, // username
                     "type": "html"


              }, {
                     "targets": 5,
                     "render": function (data, type, row) {
                            if (data) {
                                   return "<img src='/images/ts3.png' data-toggle='tooltip' data-placement='left' title='TS3 Username: "+data+"'>"
                            } else {
                                   return ''
                            }
                     }
              }, {
                     "targets": 6, // userid
                     "visible": false,
                     "searchable": false
              }],

             "drawCallback": function(oSettings) {
                     $('a[rel=popover]').popover({
                            placement: "bottom",
                            html: true,
                            trigger: "focus"
                     }).click(function(e){e.preventDefault();});
             }

       });
 //$('#userTable').$("a[rel=popover]").popover().click(function(e) {
       //e.preventDefault();
 //});

 //$("#userTable").on("click", "a[rel=popover]", function(e) {
       //e.preventDefault();
       //$(this).popover('show');
 //});

  $("#userTable").on ("mouseenter", "td", function() {
              $(this).find("span.userediticon").removeClass('hidden');
       });
 $("#userTable").on ("mouseleave", "td", function() {
              $(this).find("span.userediticon").addClass('hidden');
       });


  $("#userTable").on ("mouseenter", "tr", function() {
              $(this).find("div.btn-group").removeClass('invisible');
       });
 $("#userTable").on ("mouseleave", "tr", function() {
              $(this).find("div.btn-group").addClass('invisible');
       });



       // Event listener to the two range filtering inputs to redraw on input
       $('#mindays').change( function() {
              UserTable.draw();
       } );



$('.tagSelect').click( function(){
       var tagid = $(this).attr("etagid");

       $("#rank_selecters").children().removeClass("btn-primary").addClass('btn-default');
       $("[etagid='"+tagid+"']").addClass("btn-primary").removeClass('btn-default');

       UserTable.clear();
       UserTable.draw();
       UserTable.ajax.url("/usersajax.php?method=getByTag&tag="+tagid).load();
       UserTable.draw();
});

$(document).on("click", '.user_promote', function(event){
   event.preventDefault();
   var button = $(this);
   var userid = button.attr("memberID");
   var tagid = button.attr("newRank");
   $.ajax({
       url: "/usersajax.php?method=setNewRank&userid="+userid+"&tagid="+tagid,
       }).done(function() {
              var row = UserTable.row( button.parents('tr') );
              var rowNode = row.node();
              row.remove().draw(false)
       });
});

$(document).on("click", ".user_guildkick", function(){
       event.preventDefault();
       var button = $(this);
       var userid = button.attr("memberID");
       var tagid = '1429081' // -- Not in guild site tag <
       $.ajax({
              url: "/usersajax.php?method=setNewRank&userid="+userid+"&tagid="+tagid,
       }).done(function() {
              var row = UserTable.row( button.parents('tr') );
              var rowNode = row.node();
              row.remove().draw(false)
       });

});


$(document).on("click", ".save_accountid", function() {
       var accountid = $("#accountid_form input:text[name=accountid]").val();
       var user_id   = $("#accountid_form input:hidden[name=user_id]").val();
       var rowid     = $("#accountid_form input:hidden[name=rowid]").val();

       $.ajax({
              url: "/usersajax.php?method=updateAccountID&user_id="+user_id+"&accountid="+accountid,
       }).done(function() {

              UserTable.cell(rowid, 1).data(accountid);
       });
       $("#accounteditpopup").modal("hide");
});
$('#accountid_form').on("keyup keypress", function(e) {
  var code = e.keyCode || e.which;

  if (code  == 13) {
    e.preventDefault();
       var accountid = $("#accountid_form input:text[name=accountid]").val();
       var user_id   = $("#accountid_form input:hidden[name=user_id]").val();
       var rowid     = $("#accountid_form input:hidden[name=rowid]").val();

      $.ajax({
              url: "/usersajax.php?method=updateAccountID&user_id="+user_id+"&accountid="+accountid,
       }).done(function() {

              UserTable.cell(rowid, 1).data(accountid);
       });
       $("#accounteditpopup").modal("hide");
    return false;

  }
});



    $('#userTable').on("click", "span.userediticon", function() {
       $("#accountid_form input:text[name=accountid]").val( ($(this).attr('data') || '' ));
       $("#accountid_form input:hidden[name=user_id]").val( ($(this).attr('user_id') || '' ));
       $("#accountid_form input:hidden[name=rowid]").val( ($(this).attr('rowid') || '' ));
       $("#accounteditpopup").modal("show");
    });



$(document).on("click", ".showmodal", function(e) {
       e.preventDefault();


       $("#mynewdialog").find(".modal-body").load($(this).attr("href"));
       $("#mynewdialog").modal("show");


});



});
