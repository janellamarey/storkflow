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

function PostThenDeleteTableRow( url, controller, action, id )
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

function Delete( url, controller, action, id, tableId )
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

function DeletePostFile( url, controller, action, id, postid, filename )
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

function DeleteFile( url, controller, action, id, filename )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/filename/" + filename,
        data: {
            filename: filename
        },
        type: "POST",
        dataType: "json",
        success: function ( json ) {
            if ( json !== undefined )
            {
                if ( json.result === true )
                {
                    console.log( 'deleted' );
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

function FeaturePolls( url, controller, action, id )
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
                    if ( json.oldid !== undefined )
                    {
                        $( "table#polls #feat-mess-" + json.oldid ).removeClass( 'important' ).html( '' );
                        $( "table#polls #feat-butt-" + json.oldid ).addClass( 'action-button' ).html( 'Set featured' );
                    }

                    $( "table#polls #feat-mess-" + json.id ).addClass( 'important' ).html( 'featured' );
                    $( "table#polls #feat-butt-" + json.id ).removeClass( 'action-button' ).html( '' );
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


function VoteFromView( url, controller, action, questionId, radioGroupName, containerName, buttonContainerName, additionalLink )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/qid/" + questionId + "/oid/" + $( 'input[name="' + radioGroupName + '"]:checked' ).val(),
        type: "POST",
        dataType: "json",
        data: {
            qid: questionId,
            oid: $( 'input[name="' + radioGroupName + '"]:checked' ).val()
        },
        success: function ( json ) {
            if ( json !== undefined && json.qid !== undefined && json.oid !== undefined && json.optionresults !== undefined )
            {
                var showAllButton = $( '<a/>', { html: "Show all polls", href: additionalLink, "class": "action-button" } );
                $( "#" + buttonContainerName ).empty().append( 'You have already voted for this poll.' ).append( $( '<span/>' )
                        .append( showAllButton ) );

                var count = 0;
                for ( var i = 0; i < json.optionresults.length; i++ )
                {
                    count += parseInt( json.optionresults[i]['votes'] );
                    $( "#n-" + json.optionresults[i]['survey_option_id'] + " td:nth-child(2)" ).empty().text( json.optionresults[i]['votes'] );
                }
                $( "#t-" + json.qid ).empty().text( count + ' votes' );
                for ( var i = 0; i < json.optionresults.length; i++ )
                {
                    var percentage = 0;
                    var currentVotes = parseInt( json.optionresults[i]['votes'] );

                    if ( count > 0 && currentVotes > 0 )
                    {
                        percentage = (currentVotes * 100) / count;
                    }
                    else if ( count > 0 )
                    {
                        percentage = 0;
                    }
                    else if ( currentVotes > 0 )
                    {
                        percentage = 100;
                    }
                    else
                    {
                        percentage = 0;
                    }
                    $( "#n-" + json.optionresults[i]['survey_option_id'] + " .percentbar div" ).width( percentage + "%" );
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

function Vote( url, controller, action, questionId, radioGroupName, containerName, buttonContainerName )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/qid/" + questionId + "/oid/" + $( 'input[name="' + radioGroupName + '"]:checked' ).val(),
        type: "POST",
        dataType: "json",
        data: {
            qid: questionId,
            oid: $( 'input[name="' + radioGroupName + '"]:checked' ).val()
        },
        success: function ( json ) {
            if ( json !== undefined && json.qid !== undefined && json.oid !== undefined )
            {
                var resultButton = $( '<a/>', { html: "View result", href: "", "class": "action-button" } );
                resultButton.on( 'click', { qid: questionId, container: containerName, buttonContainer: buttonContainerName }, function ( event ) {
                    event.preventDefault();
                    new VoteResults( '', 'polls', 'getvotes', event.data.qid, event.data.container, event.data.buttonContainer );
                } );
                $( "." + containerName ).empty().html( "You have already voted this survey." );
                $( "#" + buttonContainerName ).empty().append( $( '<span/>' )
                        .append( resultButton )
                        );
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

function VoteResults( url, controller, action, questionId, containerName, buttonContainerName )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/qid/" + questionId,
        type: "POST",
        dataType: "json",
        data: {
            qid: questionId
        },
        success: function ( json ) {
            if ( json !== undefined && json.qid !== undefined && json.votes !== undefined )
            {
                var results = $( '<div/>' );
                var options = $( '<ul/>' );
                var totalVotes = 0;
                for ( a = 0; a < json.votes.length; a++ )
                {
                    totalVotes += parseInt( json.votes[a].votes );
                }

                for ( a = 0; a < json.votes.length; a++ )
                {
                    var currentVotes = parseInt( json.votes[a].votes );
                    var percentage = 0;
                    if ( totalVotes > 0 && currentVotes > 0 )
                    {
                        percentage = (currentVotes * 100) / totalVotes;
                    }
                    else if ( totalVotes > 0 )
                    {
                        percentage = 0;
                    }
                    else if ( currentVotes > 0 )
                    {
                        percentage = 100;
                    }
                    else
                    {
                        percentage = 0;
                    }

                    options.append( '<li><ul><li>' + json.votes[a].option_name + '</li>' );

                    var barContainer = $( '<li/>' );
                    var bar = $( '<div/>', { 'class': 'percentbar' } ).width( '100%' );
                    var subbar = $( '<div/>' ).width( percentage + '%' );
                    bar.append( subbar );
                    barContainer.append( bar );
                    options.append( barContainer );
                    options.append( '<li>' + json.votes[a].votes + '/' + totalVotes + '</li></ul></li>' );

                    var resultButton = $( '<a/>', { html: "Next poll", href: "", "class": "action-button" } );
                    resultButton.on( 'click', {
                        url: '',
                        controller: 'polls',
                        action: 'voteoptions',
                        questionId: json.qid,
                        radioGroupName: 'poll-radio',
                        containerName: containerName,
                        buttonContainerName: buttonContainerName
                    },
                    function ( event ) {
                        event.preventDefault();
                        new VoteOptions( event.data.url, event.data.controller, event.data.action,
                                event.data.questionId, event.data.radioGroupName,
                                event.data.containerName, event.data.buttonContainerName );
                    } );
                    $( "#" + buttonContainerName ).empty().append( $( '<span/>' )
                            .append( resultButton )
                            );
                }
                results.append( options );
                $( "." + containerName ).empty().append( results );
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

function VoteOptions( url, controller, action, questionId, radioGroupName, containerName, buttonContainerName )
{
    $.ajax( {
        url: url + "/" + controller + "/" + action + "/format/json/qid/" + questionId,
        type: "POST",
        dataType: "json",
        data: {
            qid: questionId
        },
        success: function ( json ) {
            if ( json !== undefined && json.qid !== undefined && json.hasVotedAll !== undefined && json.options !== undefined )
            {

                if ( json.hasVotedAll )
                {
                    $( "." + containerName ).remove();
                    $( ".poll-question" ).remove();
                    $( "#anosatinginmo" ).remove();
                    $( '#poll-button-changer' ).empty().html( '<span>Thanks for voting on all polls!</span>' );
                }
                else
                {
                    //append options
                    var optionsList = $( '<ul/>' );
                    for ( a = 0; a < json.options.length; a++ )
                    {
                        var optionsListItem = $( '<li>' );
                        var inputItem = $( '<input>', {
                            type: "radio",
                            name: "poll-radio",
                            value: json.options[a][ 'survey_option_id' ],
                            "class": "signup-radio"
                        } );
                        if ( a === 0 )
                        {
                            inputItem.attr( "checked", true );
                        }
                        optionsListItem.append( inputItem );
                        optionsListItem.append( json.options[a][ 'survey_option' ] );
                        optionsList.append( optionsListItem );
                    }
                    $( "." + containerName ).empty().append( optionsList );

                    //update buttons
                    $( '#poll-button-changer' ).empty().append( $( '<span/>' ).append( $( '<a/>', {
                        html: "Vote",
                        href: "",
                        "class": "action-button"
                    } ) ) );

                    $( '#poll-button-changer' ).find( 'a' ).on( 'click', {
                        url: '',
                        controller: 'polls',
                        action: 'vote',
                        questionId: json.qid,
                        radioGroupName: radioGroupName,
                        containerName: containerName,
                        buttonContainerName: buttonContainerName
                    }, function ( event ) {
                        event.preventDefault();
                        new Vote( event.data.url, event.data.controller, event.data.action,
                                event.data.questionId, event.data.radioGroupName,
                                event.data.containerName, event.data.buttonContainerName );
                    } );
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

function OrdinancePDF( data )
{
    var title = data['name'];
    var logo = data['logo'];
    var content = data['content'];
    var councilors1 = data['councilors1'];
    var councilors2 = data['councilors2'];
    var admin = data['admin'];
    var superuser = data['superuser'];
    var superadmin = data['superadmin'];

    //settings
    var doc = new jsPDF( 'p', 'pt', [ 576, 936 ] );
    var pageHeight = 842;
    var pageWidth = 595;
    var marginBottom = 50;
    var pageMaxY = pageHeight - marginBottom;

    var contentWidth = pageWidth * 0.75;
    var gutterWidth = pageWidth * 0.25;

    var contentInnerMargin = 10;
    var firstPageStartY = 200;
    var otherPageStartY = 50;

    var district1 = function ( doc ) {
        doc.setFontType( 'bold' );
        doc.customText( "DISTRICT I", { align: "center",
            relativeWidth: gutterWidth, xoffset: 0 }, 0, 130 );

        for ( var i = 0; i < councilors1.length; i++ )
        {
            if ( councilors1[i]['datauri'].length > 0 && parseInt( councilors1[i]['datauriwidth'] ) !== 0 && parseInt( councilors1[i]['datauriheight'] ) !== 0 )
            {
                doc.addImage( councilors1[i]['datauri'], 'png', 10, 130 + gutterGap * i,
                        parseInt( councilors1[i]['datauriwidth'] ), parseInt( councilors1[i]['datauriheight'] ) );
            }
            doc.setFontType( 'bold' );
            doc.text( 10, 155 + gutterGap * i, 'HON. ' + councilors1[i]['name'].toUpperCase() );
            doc.setFontType( 'normal' );
            doc.text( 10, 165 + gutterGap * i, 'City Councilor' );
        }
    };

    var district2 = function ( doc ) {
        doc.setFontType( 'bold' );
        var c2startY = 130 + gutterGap * (councilors1.length + 1);
        doc.customText( "DISTRICT II", { align: "center",
            relativeWidth: gutterWidth, xoffset: 0 }, 0, c2startY );

        for ( var i = 0; i < councilors2.length; i++ )
        {
            if ( councilors2[i]['datauri'].length > 0 && parseInt( councilors2[i]['datauriwidth'] ) !== 0 && parseInt( councilors2[i]['datauriheight'] ) !== 0 )
            {
                doc.addImage( councilors2[i]['datauri'], 'png', 10, c2startY + gutterGap * i,
                        parseInt( councilors2[i]['datauriwidth'] ), parseInt( councilors2[i]['datauriheight'] ) );
            }
            doc.setFontType( 'bold' );
            doc.text( 10, c2startY + 25 + gutterGap * i, 'HON. ' + councilors2[i]['name'].toUpperCase() );
            doc.setFontType( 'normal' );

            var sHonTitle = 'City Councilor';
            if ( councilors2[i]['name'].toUpperCase() === 'VICTORINO GUERRERO' )
            {
                sHonTitle = 'City Councilor - ABC Pres.';
            }
            doc.text( 10, c2startY + 35 + gutterGap * i, sHonTitle );
        }
    };

    var printSK = function ( doc )
    {
        doc.setFontType( 'normal' );
        doc.text( 10, 670, 'SK Federation President' );
    };

    var printadmin = function ( doc )
    {
        doc.setFontType( 'normal' );
        doc.text( 10, 710, 'Attested by:' );
        for ( var i = 0; i < admin.length; i++ )
        {
            if ( admin[i]['datauri'].length > 0 && parseInt( admin[i]['datauriwidth'] ) !== 0 && parseInt( admin[i]['datauriheight'] ) !== 0 )
            {
                doc.addImage( admin[i]['datauri'], 'png', 10, 710 + gutterGap * i,
                        parseInt( admin[i]['datauriwidth'] ), parseInt( admin[i]['datauriheight'] ) );
            }
            doc.setFontType( 'bold' );
            doc.text( 10, 740 + gutterGap * i, 'ATTY. ' + admin[i]['name'].toUpperCase() );
            doc.setFontType( 'normal' );
            doc.text( 10, 750 + gutterGap * i, 'Sangguniang Panlungsod Secretary' );
        }
    };

    var printsuperuser = function ( doc )
    {
        doc.setFontType( 'normal' );
        doc.text( 10, 790, 'Certified by:' );
        for ( var i = 0; i < superuser.length; i++ )
        {
            if ( superuser[i]['datauri'].length > 0 && parseInt( superuser[i]['datauriwidth'] ) !== 0 && parseInt( superuser[i]['datauriheight'] ) !== 0 )
            {
                doc.addImage( superuser[i]['datauri'], 'png', 10, 790 + gutterGap * i,
                        parseInt( superuser[i]['datauriwidth'] ), parseInt( superuser[i]['datauriheight'] ) );
            }
            doc.setFontType( 'bold' );
            doc.text( 10, 820 + gutterGap * i, 'HON. ' + superuser[i]['name'].toUpperCase() );
            doc.setFontType( 'normal' );
            doc.text( 10, 830 + gutterGap * i, 'City Vice Mayor/Presiding Officer' );
        }
    };

    var printsuperadmin = function ( doc )
    {
        doc.setFontType( 'normal' );
        doc.text( 10, 850, 'Approved by:' );
        for ( var i = 0; i < superadmin.length; i++ )
        {
            if ( superadmin[i]['datauri'].length > 0 && parseInt( superadmin[i]['datauriwidth'] ) !== 0 && parseInt( superadmin[i]['datauriheight'] ) !== 0 )
            {
                doc.addImage( superadmin[i]['datauri'], 'png', 10, 860 + gutterGap * i,
                        parseInt( superadmin[i]['datauriwidth'] ), parseInt( superadmin[i]['datauriheight'] ) );
            }
            doc.setFontType( 'bold' );
            doc.text( 10, 890 + gutterGap * i, 'HON. ' + superadmin[i]['name'].toUpperCase() );
            doc.setFontType( 'normal' );
            doc.text( 10, 900 + gutterGap * i, 'City Mayor' );
        }
    };

    var gutterGap = 35;
    doc.setDrawColor( 0 );
    //logo
    doc.addImage( logo, 38, 35, 80, 80 );

    //header
    doc.setFont( 'times' );
    doc.setFontSize( 9 );
    doc.customText( "Republic of the Philippines", { align: "center",
        relativeWidth: contentWidth, xoffset: gutterWidth }, 0, 60 );

    doc.setFontType( 'bold' );
    doc.setFont( 'helvetica' );
    doc.setFontSize( 10 );
    doc.customText( "CITY OF BACOOR", { align: "center",
        relativeWidth: contentWidth, xoffset: gutterWidth }, 0, 75 );

    doc.setFont( 'times' );
    doc.setFontType( 'normal' );
    doc.setFontSize( 10 );
    doc.customText( "Province of Cavite", { align: "center",
        relativeWidth: contentWidth, xoffset: gutterWidth }, 0, 90 );

    doc.setFontType( 'bold' );
    doc.setFontSize( 11 );
    doc.customText( "OFFICE OF THE SANGGUNIANG PANGLUNGSOD", { align: "center",
        relativeWidth: contentWidth, xoffset: gutterWidth }, 0, 115 );

    doc.setFont( 'helvetica' );
    doc.setFontType( 'normal' );
    doc.setFontSize( 11 );

    //title
    var titlelines = doc.splitTextToSize( title, 400 );
    var titlelinegap = 12;
    var titleIndex = 0;
    for ( ; titleIndex < titlelines.length; titleIndex++ )
    {
        doc.customText( titlelines[titleIndex], { align: "center",
            relativeWidth: contentWidth, xoffset: gutterWidth }, 0, 150 + titlelinegap * titleIndex );
    }
    firstPageStartY = 170 + titlelinegap * titleIndex;

    doc.setFont( 'helvetica' );
    doc.setFontSize( 6 );

    district1( doc );
    district2( doc );
    printSK( doc );
    printadmin( doc );
    printsuperuser( doc );
    printsuperadmin( doc );

    doc.setFontSize( 7 );
    doc.setFont( 'helvetica' );
    doc.setFontType( 'normal' );

    //content
    var lines = doc.splitTextToSize( content, 400 );
    var lineCount = 0;
    var startY = firstPageStartY;
    var lineGap = 10;

    for ( var i = 0; i < lines.length; i++ )
    {
        var nextY = startY + lineGap * lineCount;
        if ( nextY > pageMaxY )
        {
            lineCount = 0;
            startY = otherPageStartY;
            nextY = otherPageStartY;

            doc.addPage();
            doc.setDrawColor( 0 );

            //logo
            doc.addImage( logo, 38, 35, 80, 80 );

            district1( doc );
            district2( doc );
            printSK( doc );
            printadmin( doc );
            printsuperuser( doc );
            printsuperadmin( doc );
        }
        doc.text( gutterWidth + contentInnerMargin, nextY, lines[i] );
        lineCount++;
    }

    return doc;
}

function PublishPolls( url, controller, action, id )
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
                    $( "table#polls #n-" + json.id ).remove();
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






