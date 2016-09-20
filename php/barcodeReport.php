<?php
/*
User form for initiating a bulk ingest.  User must have already uploaded ingestion folders to a server-accessible folder.
Author: Terry Brady, Georgetown University Libraries

License information is contained below.

Copyright (c) 2014, Georgetown University Libraries All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer. 
in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials 
provided with the distribution. THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, 
BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES 
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) 
HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

See http://techdocs.iii.com/sierradna/

*/
include '../../web/header.php';

$CUSTOM = custom::instance();

$user = $CUSTOM->getCurrentUser();
$barcode = util::getArg("barcode","");

header('Content-type: text/html; charset=UTF-8');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php 
$header = new LitHeader("Barcode Report");
$header->litPageHeader();
?>
<style type="text/css">
  th.wide {width: 200px;}
  th.action, td.action {width: 40px;}
  #restable tr:nth-child(odd) { 
    background-color:  #EEE;  
  }
  tr.processing td, tr.processing button, tr.processing th {
    background-color: gray;
  }
  tr.PASS td, tr.PASS button, tr.PASS th {
    background-color: white;
  }
  tr.META td, tr.META button, tr.META th {
    background-color: cyan;
  }
  tr.PULL td, tr.PULL button, tr.PULL th {
    background-color: yellow;
  }
  tr.FAIL td, tr.FAIL button, tr.FAIL th {
    background-color: pink;
  }
  #gsheetdiv a {
    border: thin solid red;
    padding: 5px 10px;
    margin: 5px 10px;
    color: red;
    display: inline-block;
  }
  #dialog-msg {
    display: none;
  }
  #dialog-form {
    text-align: center;
  }
  
  button.lastbutt:hover {
    background-color: yellow;
  }
  button.lastbutt b {
    size: large;
  }


</style>
<!--http://www.w3schools.com/w3css/w3css_icons.asp-->
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script type="text/javascript" src="../../web/gsheet.js"></script>
<script type="text/javascript" src="barcode.js"></script>
</head>
<body>
<?php $header->litHeaderAuth(array(), $CUSTOM->testUserInGroup(customGU::BARCODE) || $CUSTOM->isUserViewer());?>
<input id="test" type="hidden" value="<?php echo util::getArg('test','')?>"/>
<div id="dialog-msg"></div>
<div id="dialog-form" title="Add Barcode">
  <fieldset>
  <label>Last barcode scanned</label>
  <h2 id="bcCall">Call Number</h2>
  <h4 id="bcTitle">Title</h4>
  <h2 id="bcVol">Volume</h2>
  <div>
    <button class="lastbutt" accesskey="c" status="META" status_msg="Bad Call Number">Bad <b>C</b>all Num</button>
    <button class="lastbutt" accesskey="t" status="META" status_msg="Bad Title">Bad <b>T</b>itle</button>
    <button class="lastbutt" accesskey="v" status="META" status_msg="Bad Volume">Bad <b>V</b>olume</button>
    <button class="lastbutt reset" accesskey="r" id="lbreset" status="" status_msg=""><b>R</b>eset</button>
  </div>
  </fieldset>
  <hr/>
  <p class="validateTips" id="message">Enter the next barcode.</p>
  <form>
    <fieldset>
      <label for="name">Barcode</label>
      <input type="text" name="barcode" id="barcode" value="" class="text ui-widget-content ui-corner-all">
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>

<div id="main">
  <span id='gsheetdiv'>
    <a id="addb" href="#">Add Barcode</a>
    <a id="exportGsheet" href="#">End Session - Export to Google Sheet</a>
  </span>
<div>
<table id='restable'>
<tr class='header'>
<th class='action noexport'/>
<th class='role'>Barcode</th>
<th class='role'>Location Code</th>
<th class='role wide'>Call Number</th>
<th class='role'>Volume</th>
<th class='role wide'>Title</th>
<th class='role'>Status Code</th>
<th class='role'>Due Date</th>
<th class='role'>icode2</th>
<th class='role'>Is Suppressed (Bib)</th>
<th class='role'>Record Num</th>
<th class='role'>Status</th>
<th class='role wide'>Status Note</th>
<th class='role'>Timestamp</th>
</tr>
</table>
</div>

<?php $header->litFooter();?>
</div>
</body>
</html>