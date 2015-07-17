

$(document).ready(function(){
    var gw2items = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
          //prefetch: '../data/films/post_1960.json',
        remote: {
            url: '/raffleajax.php?method=getGw2ItemsJSON&query=%QUERY',
            wildcard: '%QUERY'
        }
    });
   var gw2users = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
          //prefetch: '../data/films/post_1960.json',
        remote: {
            url: '/usersajax.php?method=findUser&query=%QUERY',
            wildcard: '%QUERY'
        }
    });
    
    $('.item_typeahead').typeahead(null, {
        name: 'gw2items',
        display: 'name',
        source: gw2items,
        highlight: true,
        hint: true,
        templates: {
            empty: [
                '<div class="empty-message">',
                'No items found that match the current query',
                '</div>'
                ].join('\n'),

            suggestion: function(data) {
                return '<div data-gw2item="'+data.id+'"class="gw2tooltip">' + '<img width="32" src="'+data.icon+'" />&nbsp;' + data.name + '</div>';
            }
        }

    }).on('typeahead:selected', function (obj, data) {
        //console.log(obj);



        $.get( "/raffleajax.php?method=addPrize&id="+data.id+"&raffle_id="+$("#raffle_id").val(), function( rdata ) {

            $('#prizeitems').prepend("<li class='list-group-item'><a href='#' rel='"+data.prize_id+"' class='rmv_prize glyphicon glyphicon-remove'></a><img width='32' src='"+data.icon+"'>"+data.name+"  ("+data.item_type+")<div class='pull-right'>"+data.tp_price_fmt+"</div></li>");
            $('.item_typeahead').attr("value", "");
            $('.item_typeahead').typeahead('close');
        });
    });;

   
   
   
   
   
   
   
   
   
   
   
   
   $('.user_typeahead').typeahead(null, {
        name: 'gw2users',
        display: 'username',
        source: gw2users,
        highlight: true,
        hint: true,
        templates: {
            empty: [
                '<div class="empty-message">',
                'No users found that match the current query',
                '</div>'
                ].join('\n'),
          suggestion: function(data) {
                return '<div>['+data.account_id+'] <b>'+ data.username + '</b></div>';
            }
        }
    })
    .on('typeahead:open',function(){$('.tt-menu').css('width','400px'); })
    .on('typeahead:selected', function (obj, data) {
        //console.log(obj);
        $('#entry_user_id').val(data.user_id);
        $('#entry_username').val(data.username);
        $('#entry_account_id').val(data.account_id);

    });
    

    $("#add_entry").submit(function() {
    
        entry_amount = $('#entry_amt').val();
        user_id = $('#entry_user_id').val();
        username = $('#entry_username').val();
        account_id = $('#entry_account_id').val();
        
        tval = true;
        
        if (!username) {
            alert('Please enter a user');
            return false;
        }
        
        console.log(entry_amount);
        if (!entry_amount ||entry_amount == 0) {
            alert('Please enter an entry amount');
            return false;
        } 
        
        if (tval ) {
            
            $.get( "/raffleajax.php?method=addEntry&user_id="+user_id+"&amt="+entry_amount+"&raffle_id="+$("#raffle_id").val(), function( rdata ) {
                tot_entries = $("#total_entries").html();
                tot_gold = $("#total_gold").html();
                
                tot_entries = parseInt(tot_entries)+1
                tot_gold = parseInt(tot_gold) + parseInt(entry_amount)
                
                $("#total_entries").html(tot_entries)
                $("#total_gold").html(tot_gold)
                
                $("entry_username").attr("value", "");
                
            });
            
            $('#raffle_entries').prepend("<li class='list-group-item'><a href='#' rel='"+user_id+"' class='rmv_entry glyphicon glyphicon-remove'></a>&nbsp;"+username+"<span class='user_entry_amt pull-right'>"+entry_amount+"<img src='/images/Gold_coin.png' ></span> </li>");
        }
        
        return false;
    });
        
    $("#rafflelist tr").on('click', function() {
        rid = $(this).find('td:nth-child(2)').text()
        
        
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname
        document.location = newurl + "?raffle_id="+rid;
    });
    
    
    $(document).on("click", '.rmv_entry', function(event){
        event.preventDefault();
        var li = $(this);
        var user_id = $(this).attr("user_id");
        var user_amt = $(this).attr("entry_amt");

        tot_entries = $("#total_entries").html();
        tot_gold = $("#total_gold").html();
        tot_entries = parseInt(tot_entries)-1
        tot_gold = parseInt(tot_gold || 0) - parseInt(user_amt)
                
        $("#total_entries").html(tot_entries)
        $("#total_gold").html(tot_gold)
        
        
        $.ajax({
            url: "/raffleajax.php?method=removeEntry&user_id="+user_id+"&raffle_id="+$("#raffle_id").val(),
        }).done(function() {
            li.closest('li').remove();
        });
    });

   $(document).on("click", '.raffle_upd', function(event){
        event.preventDefault();
        var action = $(this).attr("rel");
        var raffle_id = $(this).attr("raffle_id");

        $.ajax({
            url: "/raffleajax.php?method=deleteRaffle&id="+raffle_id,
        }).done(function() {
            location.reload();
        });
    });


   $(document).on("click", '.rmv_prize', function(event){
        event.preventDefault();
        var li = $(this);
        var prize_id = $(this).attr("prize_id");

        $.ajax({
            url: "/raffleajax.php?method=removePrize&id="+prize_id,
        }).done(function() {
            li.closest('li').remove();
        });
    });
   
    $("#rafflelist").on ("mouseenter", "tr", function() {
        $(this).find("div.btn-group").removeClass('invisible');
    });
    $("#rafflelist").on ("mouseleave", "tr", function() {
        $(this).find("div.btn-group").addClass('invisible');
    });   

});


(function($){
    $.gw2tooltip('[data-gw2item]', {
        localStorage:true
    });
})(jQuery);