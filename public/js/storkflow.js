function OutOfStock( obj )
{
    //object should have the following properties: url, controller, action, id, status
    $.ajax( {
        url: obj.url + "/" + obj.controller + "/" + obj.action + "/format/json/stockId/" + obj.stockId + "/status/" + obj.status,
        data: {
            stockId: obj.stockId,
            status: obj.status
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined && json.result )
            {
                obj.onSuccess();
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr );
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function NewDelete( obj )
{
    //object should have the following properties: url, controller, action, id
    $.ajax( {
        url: obj.url + "/" + obj.controller + "/" + obj.action + "/format/json/id/" + obj.id,
        data: {
            id: obj.id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined && json.result )
            {
                obj.onSuccess();
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr );
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function NewDeleteFile( obj )
{
    //object should have the following properties: url, controller, action, id, filename
    $.ajax( {
        url: obj.url + "/" + obj.controller + "/" + obj.action + "/format/json/filename/" + obj.filename,
        data: {
            filename: obj.filename
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined && json.result )
            {
                obj.onSuccess();
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr.responseText );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function Delete( url, controller, action, id )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                if ( json.result )
                {
                    $( "#item-" + id ).remove();
                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr );
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function PostThenDeleteTableRow( obj )
{
    var url = obj.url;
    var controller = obj.controller;
    var action = obj.action;
    var id = obj.id;
    var tableId = obj.tableId;

    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined && json.result )
            {
                $( "table#" + tableId + " #n-" + id ).remove();
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function SendOrder( obj )
{
    var url = obj.url;
    var controller = obj.controller;
    var action = obj.action;
    var id = obj.id;

    $.ajax( {
        url: url + "/" + controller + "/" + action + '/format/json/' + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined && json.result && json.message )
            {
                $( "#message" ).empty().append( json.message );
                $( "#checkout-cart" ).remove();
                $( "#customer-order" ).remove();
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr );
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

//other codes
function Approve( url, controller, action, id )
{

    $( "#approve-butt-" + id ).parent().empty();
    $( "#appr-mess-" + id ).empty().html( "<img src='/img/spinner_squares_circle.gif'/><h4>Please wait we are processing your request.</h4>" );

    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                $( "#appr-mess-" + id ).empty().html( json.message );

                if ( json.result )
                {
                    $( "#approve-butt-" + id ).parent().empty();
                    $( "#approvals" ).find( 'tbody' ).empty();

                    var addRow = function ( user )
                    {
                        var username = user.firstname + ' ' + user.lastname;
                        var signature = $( '<img>' ).attr( 'src', '/img/users/' + user.user_id + '/tiny/' + user.signature ).attr( 'alt', '(No signature)' );
                        var sign = $( '<td>' ).attr( 'id', 'n-' + user.id ).append( username ).append( signature );
                        var row = $( '<tr>' ).append( sign ).append( $( "<td>" ) );
                        $( "#approvals" ).find( "tbody" ).append( row );
                    };

                    var addTextRow = function ( text )
                    {
                        $( "#approvals" ).find( "tbody" ).append( $( '<td>' + text + '</td>' ) );
                    }

                    var add2TextRow = function ( text1, text2 )
                    {
                        $( "#approvals" ).find( "tbody" ).append( $( '<td>' + text1 + '</td>' ) ).append( $( '<td>' + text2 + '</td>' ) );
                    }

                    var addTwoFillRow = function ( user1, user2 )
                    {
                        var sign1 = $( "<td>&nbsp;</td>" );
                        if ( user1 !== '' )
                        {
                            var username1 = user1.firstname + ' ' + user1.lastname;
                            var signature1 = $( '<img>' ).attr( 'src', '/img/users/' + user1.user_id + '/tiny/' + user1.signature ).attr( 'alt', '(No signature)' );
                            sign1 = $( '<td>' ).attr( 'id', 'n-' + user1.id ).append( username1 ).append( signature1 );
                        }
                        var sign2 = $( "<td>&nbsp;</td>" );
                        if ( user2 !== '' )
                        {
                            var username2 = user2.firstname + ' ' + user2.lastname;
                            var signature2 = $( '<img>' ).attr( 'src', '/img/users/' + user2.user_id + '/tiny/' + user2.signature ).attr( 'alt', '(No signature)' );
                            sign2 = $( '<td>' ).attr( 'id', 'n-' + user2.id ).append( username2 ).append( signature2 );
                        }

                        var row = $( '<tr>' ).append( sign1 ).append( sign2 );
                        $( "#approvals" ).find( "tbody" ).append( row );
                    }

                    var rebuild = function ( json )
                    {
                        if ( json.councilors.length > 0 )
                        {
                            json.district1 = [ ];
                            json.district2 = [ ];
                            for ( i = 0; i < json.councilors.length; i++ )
                            {
                                if ( json.councilors[i].district === '1' )
                                {
                                    json.district1.push( json.councilors[i] );
                                }
                                else
                                {
                                    json.district2.push( json.councilors[i] );
                                }
                            }
                        }

                        if ( json.superadmins !== undefined )
                        {
                            for ( i = 0; i < json.superadmins.length; i++ )
                            {
                                addRow( json.superadmins[i] );
                            }
                        }
                        if ( json.superusers !== undefined )
                        {
                            for ( i = 0; i < json.superusers.length; i++ )
                            {
                                addRow( json.superusers[i] );
                            }
                        }
                        if ( json.district1 !== undefined && json.district1.length > 0 && json.district2 !== undefined && json.district2.length > 0 )
                        {
                            add2TextRow( '<b>DISTRICT 1</b>', '<b>DISTRICT 2</b>' );
                            var min = Math.min( json.district1.length, json.district2.length );
                            var diff = json.district1.length - json.district2.length;
                            var i = 0;
                            for ( ; i < min; i++ )
                            {
                                addTwoFillRow( json.district1[i], json.district2[i] );
                            }

                            for ( j = i; j < i + Math.abs( diff ); j++ )
                            {
                                if ( diff < 0 )
                                {
                                    addTwoFillRow( '', json.district2[j] );
                                }
                                else if ( diff > 0 )
                                {
                                    addTwoFillRow( json.district1[j], '' );
                                }
                            }
                        }
                        else if ( json.district1 !== undefined && json.district1.length > 0 )
                        {
                            addTextRow( '<b>DISTRICT 1</b>' );
                            for ( i = 0; i < json.district1.length; i++ )
                            {
                                addRow( json.district1[i] );
                            }
                        }
                        else if ( json.district2 !== undefined && json.district2.length > 0 )
                        {
                            addTextRow( '<b>DISTRICT 2</b>' );
                            for ( i = 0; i < json.district2.length; i++ )
                            {
                                addRow( json.district2[i] );
                            }
                        }
                    };

                    rebuild( json );

                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function ApproveInList( url, controller, action, id )
{

    $( "#approve-butt-" + id ).parent().empty();
    $( "#appr-mess-" + id ).empty().html( "<img src='/img/spinner_squares_circle.gif'/><h4>Please wait we are processing your request.</h4>" );

    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                $( "#appr-mess-" + id ).empty().html( json.message );

                if ( json.result && json.approvalText !== undefined )
                {
                    $( "#approval-" + id ).empty().text( json.approvalText );
                }
                else
                {
                    $( "#approval-" + id ).empty().text( "" );
                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function PublishInList( url, controller, action, id )
{
    $( "#approve-butt-" + id ).parent().empty();
    $( "#appr-mess-" + id ).empty().html( "<img src='/img/spinner_squares_circle.gif'/><h4>Please wait we are processing your request.</h4>" );

    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                $( "#appr-mess-" + id ).empty().html( json.message );
                $( "table#ordinances #n-" + id ).remove();
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );

}

function ApproveUser( url, controller, action, id )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                if ( json.result && json.id === parseInt( id ) )
                {
                    $( "table#users #n-" + json.id ).remove();
                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function PostDeleteTableRow( url, controller, action, id, tableId )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                if ( json.result )
                {
                    $( "table#" + tableId + " #n-" + id ).remove();
                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr );
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function DeletePostFile( url, controller, action, id )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined && json.result === true )
            {
                $( "#li-" + id ).empty().remove();
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr.responseText );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function DeleteFileByPostIdAndFilename( url, controller, action, id, postid, filename )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/filename/" + filename + "/postid/" + postid,
        data: {
            filename: filename,
            postid: postid
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined && json.result === true )
            {
                $( "#li-" + id ).empty().remove();
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr.responseText );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}


function AddToCart( url, controller, action, id )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                if ( json.result === true && json.item !== undefined )
                {
                    console.log( 'added to cart' );
                    console.log( json.item );
                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr.responseText );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function DeleteOrdinanceFile( url, controller, action, id, ordinanceId, filename )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/filename/" + filename + "/ordinance_id/" + ordinanceId + "/file_id/" + id,
        data: {
            file_id: id,
            ordinance_id: ordinanceId,
            filename: filename,
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                if ( json.result === true )
                {
                    $( "#li-" + id ).empty().remove();
                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr.responseText );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function ApproveAndPublish( url, controller, action, id )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                if ( json.result && json.id === parseInt( id ) )
                {
                    $( "table#ordinances #n-" + json.id ).remove();
                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function ApproveAndPublishInView( url, controller, action, id )
{
    $( "#approve-butt-" + id ).parent().empty();
    $( "#appr-mess-" + id ).empty().html( "<img src='/img/spinner_squares_circle.gif'/><h4>Please wait we are processing your request.</h4>" );

    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/id/" + id,
        data: {
            id: id
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                if ( json.result && json.id === parseInt( id ) )
                {
                    $( "#" + json.messagecontainer ).empty().html( json.message );
                    $( "#pub-butt-" + json.id ).parent().empty();
                    text = $( "#approvals-" + json.id + " tr:first" ).find( "td:first" ).html();
                    if ( text.localeCompare( '<p>There are no approvals for this ordinance.</p>' ) === 0 )
                    {
                        $( "#approvals-" + json.id ).find( 'tbody' ).empty();
                    }
                    $( "#approvals-" + json.id ).find( 'tbody' )
                            .append( $( '<tr>' ).attr( 'id', 'n-' + json.approveid )
                                    .append( $( '<td>' ).append( json.user.firstname + ' ' + json.user.lastname ) )
                                    .append( $( '<td>' ).append( $( '<img>' ).attr( 'src', '/img/users/' + json.user.id + '/tiny/' + json.user.signature ).attr( 'alt', '(No signature)' ) ) )
                                    );
                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function SimpleAction( url, controller, action )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/",
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                if ( json.result && json.message !== undefined )
                {
                    $( '#button-messages' ).empty().append( json.message );
                }
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr );
            alert( errorThrown );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function Combobox( data, id, name, extension )
{
    var select = $( "<select/>", { id: name, name: name } );
    var options = [ ];
    for ( var i = 0; i < data.length; i++ )
    {
        var option = '<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>';
        options.push( option );
    }
    select.append( options.join( "" ) );

    $( "#" + id ).append( select );

    if ( extension !== '' )
    {
        $( "#" + extension ).empty().append( data[parseInt( select.val() ) - 1]['description'] );

        select.on( "change", { values: data }, function ( eventObject ) {
            $( "#" + extension ).empty().append( eventObject.data.values[parseInt( $( this ).val() ) - 1]['description'] );
        } );
    }
}

function Confirm( title, message, yesText, yesCallback )
{
    $( "<div></div>" ).dialog( {
        buttons: [ {
                text: yesText,
                click: function () {
                    yesCallback();
                    $( this ).remove();
                }
            },
            {
                text: "Cancel",
                click: function () {
                    $( this ).remove();
                }
            }
        ],
        close: function ( event, ui ) {
            $( this ).remove();
        },
        resizable: false,
        title: title,
        modal: true
    } ).text( message ).parent().addClass( "alert" );
}

function QtyDialog( stockId )
{
    $( "#cart-form" ).attr( { title: "Enter quantity" } ).empty();
    var label = $( "<label>Quantity:</label>" );
    var text = $( "<input></input>" ).attr( { "type": "text", "name": "qty" } ).css( { marginLeft: "10px", width: "70%" } );
    var stockElem = $( "<input></input>" ).attr( { "type": "hidden", "id": "stock-id" } ).val( stockId );
    var form = $( "<form></form>" ).append( label ).append( stockElem ).append( text );
    $( "#cart-form" ).append( form );

    $( "#cart-form" ).dialog( {
        buttons: [ {
                text: "Submit",
                click: function () {
                    var qty = $( "#cart-form :text" ).val();
                    var stockId = $( "#cart-form #stock-id" ).val();
                    $.ajax( {
                        url: "/items/tocart/format/json/id/" + stockId + "/qty/" + qty,
                        data: {
                            id: stockId,
                            qty: qty
                        },
                        type: "POST",
                        dataType: "json",
                        success: function ( json ) {
                            if ( json !== undefined && json.result === true && json.items !== undefined )
                            {
                                new UpdateCart( json.items, { cartId: 'checkout-cart', tableId: 'checkout-cart-table', deleteButtonId: 'checkout-delcart-butt-' } );
                                new UpdateRemoveButtonListenerInCart( json.items, { deleteButtonId: 'checkout-delcart-butt-' } );
                                new UpdateCheckoutButton( json.items, { cartId: 'checkout-button' } );
                            }
                        },
                        error: function ( xhr, status, errorThrown )
                        {
                            alert( xhr.responseText );
                        },
                        complete: function ( xhr, status )
                        {
                        }
                    } );

                    $( this ).dialog( "close" );
                    $( "#cart-form" ).empty();
                }
            },
            {
                text: "Cancel",
                click: function () {
                    $( this ).dialog( "close" );
                    $( "#cart-form" ).empty();
                }
            } ],
        close: function ( event, ui )
        {
            $( this ).dialog( "close" );
        },
        height: 150,
        width: 300,
        resizable: true,
        modal: true
    } );
}

function DeleteItemFromCart( stockId )
{
    $.ajax( {
        url: "/items/deletefromcart/format/json/id/" + stockId,
        data: {
            id: stockId
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined && json.result === true && json.items !== undefined )
            {
                new UpdateCart( json.items, { cartId: 'checkout-cart', tableId: 'checkout-cart-table', deleteButtonId: 'checkout-delcart-butt-' } );
                new UpdateRemoveButtonListenerInCart( json.items, { deleteButtonId: 'checkout-delcart-butt-' } );
                new UpdateCheckoutButton( json.items, { cartId: 'checkout-button' } );
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr.responseText );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function DeleteItemFromCheckoutCart( stockId )
{
    $.ajax( {
        url: "/items/deletefromcart/format/json/id/" + stockId,
        data: {
            id: stockId
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined && json.result === true && json.items !== undefined )
            {
                new UpdateCart( json.items, { cartId: 'checkout-cart', tableId: 'checkout-cart-table', deleteButtonId: 'checkout-delcart-butt-' } );
                new UpdateRemoveButtonListenerInCheckoutCart( json.items, { deleteButtonId: 'checkout-delcart-butt-' } );
                new UpdateCustomerOrderForm( json.items );
            }
        },
        error: function ( xhr, status, errorThrown )
        {
            alert( xhr.responseText );
        },
        complete: function ( xhr, status )
        {
        }
    } );
}

function UpdateRemoveButtonListenerInCheckoutCart( itemObj, container )
{
    if ( !($.isArray( itemObj.items ) && itemObj.items.length === 0) )
    {
        $.each( itemObj.items, function ( key, item ) {
            $.each( item, function ( key, unit ) {
                $( "#" + container.deleteButtonId + unit.stock_id ).on( "click", { id: unit.stock_id }, function ( event ) {
                    new Confirm( "Delete item in cart", "Are you sure you want to delete this item?", "Yes", function () {
                        new DeleteItemFromCheckoutCart( event.data.id );
                    } );
                    event.preventDefault();
                } );
            } );
        } );
    }
}

function UpdateRemoveButtonListenerInCart( itemObj, container )
{
    if ( !($.isArray( itemObj.items ) && itemObj.items.length === 0) )
    {
        $.each( itemObj.items, function ( key, item ) {
            $.each( item, function ( key, unit ) {
                $( "#" + container.deleteButtonId + unit.stock_id ).on( "click", { id: unit.stock_id }, function ( event ) {
                    new Confirm( "Delete item in cart", "Are you sure you want to delete this item?", "Yes", function () {
                        new DeleteItemFromCart( event.data.id );
                    } );
                    event.preventDefault();
                } );
            } );
        } );
    }
}

function UpdateCheckoutButton( itemObj, container )
{
    if ( !($.isArray( itemObj.items ) && itemObj.items.length === 0) )
    {
        var checkoutButton = $( "<a>Checkout</a>" ).attr( { id: "checkout-butt", "class": "action-button", href: "/buy/index" } );
        $( "#" + container.cartId ).empty().append( $( "<div></div>" ).attr( { "class": "block" } ).append( checkoutButton ) );
    }
    else
    {
        $( "#" + container.cartId ).empty();
    }
}

function UpdateCustomerOrderForm( itemObj )
{
    if ( $.isArray( itemObj.items ) && itemObj.items.length === 0 && $( "#customer-order" ).length )
    {
        $( "#customer-order" ).empty().remove();
    }
}

function UpdateCart( itemObj, container )
{
    if ( !($.isArray( itemObj.items ) && itemObj.items.length === 0) )
    {
        var cartElement = $( "#" + container.cartId ).empty();
        var table = $( "<table></table>" ).attr( { id: container.tableId } );
        var tbody = $( "<tbody></tbody>" );
        var headerRow = $( "<tr></tr>" );
        var itemHeader = $( "<th>Item</th>" );
        var qtyHeader = $( "<th>Quantity</th>" );
        var subtotalHeader = $( "<th>Total</th>" );
        var nbspHeader = $( "<th>&nbsp;</th>" );

        cartElement.append( table );
        table.append( tbody ).append( headerRow.append( itemHeader ).append( qtyHeader ).append( subtotalHeader ).append( nbspHeader ) );
        var items = itemObj.items;
        $.each( items, function ( key, item ) {

            table.append( $( "<tr/>" ).append( $( "<th/>" ).append( key ) )
                    .append( $( "<th>&nbsp;</th>" ) )
                    .append( $( "<th>&nbsp;</th>" ) )
                    .append( $( "<th>&nbsp;</th>" ) ) );

            $.each( item, function ( key, unit ) {
                var title = $( "<td/>" ).append( unit.title + "(PHP" + unit.price + ")" );
                var qty = $( "<td/>" ).append( "x" + unit.qty );
                var lineCost = $( "<td/>" ).append( "PHP " + unit.lineCost );
                var deleteButton = $( "<td/>" ).append( $( "<a>Remove</a>" ).attr( { id: container.deleteButtonId + unit.stock_id, "class": "action-button", href: "" } ) );
                var row = $( "<tr/>" ).attr( { id: "n-" + unit.stock_id } ).append( title ).append( qty ).append( lineCost ).append( deleteButton );
                table.append( row );
            } );
        } );
        table.append( $( "<tr/>" ).append( $( "<td>&nbsp;</td>" ) ).append( $( "<td>&nbsp;</td>" ) ).append( $( "<td/>" ).append( "PHP " + itemObj.total ) ) );
    }
    else
    {
        $( "#" + container.cartId ).empty().append( $( "<h3>No items in cart.</h3>" ) );
    }
}

function Populate( post )
{
    var keys = [ ];
    for ( var key in post )
    {
        if ( post.hasOwnProperty( key ) )
        {
            keys.push( key );
        }
    }
    for ( var i = 0; i < keys.length; i++ )
    {
        $( "#" + keys[i] ).val( post[keys[i]] );
    }
}







