<style>
    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: #636b6f;
        margin: 0;
        padding: 0;
        background-color: #f8fafc;
    }

    .mail-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 5px;
        border: 1px solid #e6e6e6;
    }

    .mail-header {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }

    .mail-body {
        font-size: 16px;
        line-height: 1.5;
        color: #333;
        margin-bottom: 20px;
    }

    .mail-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #b0b0b0;
    }

    img {
        max-width: 100%;
        height: auto;
    }
</style>

<div class='mail-container' style="background-color: #edf2f7">
    <div class='mail-footer'>
        <!-- <img src="{{asset('storage/envase_etif.png')}}" style="width: 350px;"/>-->
        <img src="{{ asset('storage/' . $details['exposicion']['logo_exposicion']) }}" 
            alt="Logo exposición"
            style="max-height: 350px;" />
    </div>
    <div class='mail-header'>
        {{$details['title']}}
    </div>
    <div class='mail-body'>
        {!! $details['body'] !!}
    </div>

    <div style='text-align: right;'>
        <a class='button button-primary' href='http://gexpos.com/'>Ingresar</a>
    </div>    
</div>