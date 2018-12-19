<?php
  $_GET["help-titel"] = "Så här använder du sidan för LÄROMEDEL";
  $_GET["help-content"] = "
      <h3>På den här sidan kan du:</h3>
      <ul>
      <li><strong>Se en lista på läromedel</strong> som du kan boka till kurser.</li>
      <li><strong>Se information</strong> om ett visst läromedel.</li> 
      <li><strong>Se tillgängligheten</strong> för ett visst läromedel. 
        <em>Observera dock att datan för den informationen kan vara otillräcklig i slutet och början på läsår. En notis överst på sidan finns då för att uppmärksamma detta</em>.</li>
      <li><strong>Boka ett läromedel</strong> till en kurs</li>
      </ul>
      
      <h3>Så här ser du information:</h3>
      <ul>
      <li><strong>Välj för vilken termin</strong> du vill se tillgänglighet eller information med den blå dropdown-knappen (som har etiketten 'Visar tillgänglighet för...')</li>
      <li><strong>För att se information</strong>
        <ul>
          <li>Leta upp läromedlet i listan och klicka på titeln. Använd visa-knappen för att se mer information</li>
        </ul>
      </li>
      <li><strong>För att se tillgänglighet</strong>
        <ul>
          <li>Varje läromedel har tillgänglighetsinformation i slutet på raden. Antalet tillgängliga av beståndet visas för vald termin</li>
          <li><span style=\"color: darkgreen\">Grön färg</span> betyder <strong>god</strong> tillgänglighet</li>
          <li><span style=\"color: darkorange\">Orange färg</span> betyder <strong>varnar</strong> för låg tillgänglighet (färre än ".Config::BOK_INSTOCK_WARNING.")</li>
          <li><span style=\"color: red\">Grön färg</span> betyder att läromedlet är <strong>slut eller överbokat</strong></li>
        </ul>
      </li>
      </ul>
      
      <h3>Så här bokar du:</h3>
      <p>Observera att du även kan boka från sidorna 'Kurser' och 'Bokningar'. Det spelar ingen roll vilken sida du använder</p>
      <ul>
      <li><strong>Leta upp läromedlet</strong> du vill boka till en kurs</li>
      <li><strong>klicka på Boka-knappen</strong> bredvid titeln</li>
      <li>Du kommer då till boknings-sidan med valt läromedel ifyllt. Använd hjälpen där för att se vad du ska göra</li>
      </ul>
  ";
?>

