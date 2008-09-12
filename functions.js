function up_launchWM( userID, destinationUserID, destinationName )
{
    window.open( "/userplane/wm.php?strDestinationUserID=" + destinationUserID, "WMWindow_" + up_replaceAlpha(userID) + "_" + up_replaceAlpha(destinationUserID), "width=468,height=595,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1" );
}
function up_launchUL( )
{
    window.open( "ul.php", "UL_Window", "width=220,height=700,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=1" );
}

function up_replaceAlpha( strIn )
{
    var strOut = "";
    for( var i = 0 ; i < strIn.length ; i++ )
    {
        var cChar = strIn.charAt(i);
        if( ( cChar >= 'A' && cChar <= 'Z' )
                || ( cChar >= 'a' && cChar <= 'z' )
                || ( cChar >= '0' && cChar <= '9' ) )
        {
            strOut += cChar;
        }
        else
        {
            strOut += "_";
        }
    }
    
    return strOut;
}

