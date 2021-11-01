<!DOCTYPE html>
<html>
<head>
  <title>Sharepeeps</title>
  <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
  <style type="text/css">
    html {
      font-family: sans-serif;
      -ms-text-size-adjust: 100%;
      -webkit-text-size-adjust: 100%;
    }
    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    main,
    menu,
    nav,
    section,
    summary {
      display: block;
    }
    audio,
    canvas,
    progress,
    video {
      display: inline-block;
      vertical-align: baseline;
    }
    a:active,
    a:hover {
      outline: 0;
    }
    b,
    strong {
      font-weight: bold;
    }
    small {
      font-size: 80%;
    }
    sub,
    sup {
      font-size: 75%;
      line-height: 0;
      position: relative;
      vertical-align: baseline;
    }
    img {
      border: 0;
    }
    button {
      overflow: visible;
    }
    body {
      font-family: "Ubuntu", Helvetica, Arial, sans-serif;
      font-size: 14px;
      line-height: 1.42857143;
      color: #333333;
      background-color: #ffffff;
      margin:0;
    }
    input,
    button,
    select,
    textarea {
      font-family: inherit;
      font-size: inherit;
      line-height: inherit;
    }
    a {
      color: #337ab7;
      text-decoration: none;
    }
    a:hover,
    a:focus {
      color: #23527c;
      text-decoration: underline;
    }
    a:focus {
      outline: 5px auto -webkit-focus-ring-color;
      outline-offset: -2px;
    }
    img {
      vertical-align: middle;
    }
    .clearfix:before,
    .clearfix:after,
    .container:before,
    .container:after,
    .container-fluid:before,
    .container-fluid:after,
    .row:before,
    .row:after {
      content: " ";
      display: table;
    }
    .clearfix:after,
    .container:after,
    .container-fluid:after,
    .row:after {
      clear: both;
    }
   /* .wrapper{
      display: block;
      margin:0 auto;
      background-color: #fff;
      max-width: 600px;
    }*/
    /*.header h1 {
      display: block;
      font-size: 18px;
      margin: 0;
      text-align: center;
      padding: 15px 0;
      background-color: #333;
      color:#fff;
    }*/
    .logo-holder img{
      margin: 0 auto;
      max-width: 65px;
    }

    .details-table td{
      padding: 0;
      vertical-align: baseline;
      border-right: 1px solid #d3d3d3;
    }
    .details-table td:last-child{
      border:0;
    }
    .banner-holder img {
      display: block;
      max-width: 100%;
      height: auto;
    }
    /*.btn {
      display: inline-block;
      font-weight: 400;
      text-align: center;
      white-space: nowrap;
      vertical-align: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      border: 1px solid transparent;
      padding: .5rem 1.75rem;
      font-size: 1rem;
      line-height: 1.25;
      border-radius: .25rem;
      transition: all .15s ease-in-out;
    }
    .btn-success {
      color: #fff !important;
      background-color: #7DBC63;
      border-color: #7DBC63;
    }
    .footer{
      display: block;
      background-color:#7DBC63;
      height: 70px;
      padding:10px;
    }
    .social-icon{
      list-style: none;
      margin:20px auto 0;
      display: block;
      text-align: center;
    }*/
    .social-icon li{
      display: inline-block;
      margin:0 10px 0 0;
    }
  </style>
</head>
<body style="background-color: #eee;">
<div class="wrapper" style="display: block;margin:0 auto;background-color: #fff;max-width: 600px;">
  <div class="banner-holder">
    <img src="{!! asset('assets/images/banner.jpg') !!}" alt="banner" />
  </div>
  <div style="display: block;padding:10px">
    <strong style="font-size: 20px;">{{_lang('Hi Admin')}},</strong>
    
      <p style="font-size: 16px;">{{$username}} {{_lang('Has reported a post')}} "{{$postname}}"  {{_lang('and  message is "')}} {{$msg}} {{_lang('"')}}.<br/> {{_lang('Please Login blow')}}</p>
   
    <a style="display: inline-block;font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;padding: .5rem 1.75rem;font-size: 1rem;line-height: 1.25;border-radius: .25rem;transition: all .15s ease-in-out;color: #fff !important;
      background-color: #7DBC63;border-color: #7DBC63;" href="{!! URL::route('login') !!}" class="btn btn-success">{{_lang('Click Here')}}</a>
    <table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="container-middle">
      <tbody>
      <tr>
        <td>
          <table border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
              <td class="high-top" height="80"></td>
            </tr>
            {{--<tr>--}}
              {{--<td class="h-t" align="center" style="font-family:Myriad Pro; font-size:2.5em;font-weight: bold;text-transform: uppercase; color:#191626; line-height: 25px;"class="editable">--}}
                {{--Contact--}}
              {{--</td>--}}
            {{--</tr>--}}
            <tr>
              <td height="20">&nbsp;</td>
            </tr>
            <tr>
              <td class="border" style="padding:0em;text-align:center;">
                <img src="{!! asset('assets/images/border.png') !!}" style="display:inline-block;width:180px;height:auto;">
              </td>
            </tr>
            <td height="20"></td>
            <tr>
              <td>
                <table border="0" width="200" height="105" align="left" cellpadding="0" cellspacing="0" class="4_grids">
                  <tbody>
                  <tr>
                  <tr>
                    <td align="center" >
                      <img src="{!! asset('assets/images/mb.png') !!}" alt=" " width="36" height="36">
                    </td>
                  </tr>
                  <tr>
                    <td style="color:#777; font-size:14px; text-align:center; font-family:Myriad Pro; line-height:0.5em;">
                      Tlf. 70 60 60 11
                    </td>
                  </tr>
                  </tr>
                  </tbody>
                </table>
                {{--<table border="0" width="200" height="105" align="left" cellpadding="0" cellspacing="0" class="4_grids">--}}
                  {{--<tbody>--}}
                  {{--<tr>--}}
                  {{--<tr>--}}
                    {{--<td align="center" >--}}
                      {{--<img src="{!! asset('assets/images/map.png') !!}" alt=" " width="36" height="36">--}}
                    {{--</td>--}}
                  {{--</tr>--}}
                  {{--<tr>--}}
                    {{--<td style="color:#777; font-size:14px; text-align:center; font-family:Myriad Pro; line-height:0.5em;">--}}
                      {{--Sharepeeps - Vinhusgade 11B - 4700 Næstved--}}
                    {{--</td>--}}
                  {{--</tr>--}}
                  {{--</tr>--}}
                  {{--</tbody>--}}
                {{--</table>--}}
                <table border="0" width="200" height="105" align="left" cellpadding="0" cellspacing="0" class="4_grids">
                  <tbody>
                  <tr>
                  <tr>
                    <td align="center" >
                      <img src="{!! asset('assets/images/mail.png') !!}" alt=" " width="36" height="36">
                    </td>
                  </tr>
                  <tr>
                    <td style="color:#777; font-size:1.15em; text-align:center; font-family:Myriad Pro; line-height:0.5em;">
                      <a style="color: #777; text-decoration: none;"  href="mailto:support@sharepeeps.dk">support@sharepeeps.dk</a>
                    </td>
                  </tr>
                  </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td height="20"></td>
      </tr>
      </tbody>
    </table>
  </div>
  <div class="footer" style="display: block;background-color:#7DBC63;height: 70px;padding:10px;">
    <ul class="social-icon" style="list-style: none;margin:20px auto 0;display: block;text-align: center;">
      <li>
        <a href="javascript: void(0)"><img src="{!! asset('assets/images/1.png') !!}" alt="facebook"></a>
      </li>
      {{--<li>--}}
        {{--<a href="javascript: void(0)"><img src="{!! asset('assets/images/2.png') !!}" alt="facebook"></a>--}}
      {{--</li>--}}
      {{--<li>--}}
        {{--<a href="javascript: void(0)"><img src="{!! asset('assets/images/3.png') !!}" alt="facebook"></a>--}}
      {{--</li>--}}
    </ul>
  </div>
</div>
</body>
</html>