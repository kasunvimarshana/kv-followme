<!-- h1><b>Title</b></h1 -->

@isset($tW)
    
    @if( (isset($userObjectArray)) )
        
        @if ( (count($userObjectArray, 0) > 1) )
            <p>Dear All,</p>
        @else
            <p>Dear {{ array_shift( $userObjectArray )->cn }},</p>
        @endif

    @endif

    <p><strong>You have a new 3W information, Please action</strong></p>
    <!-- style="border: 1px solid black;" -->
    <table style="width: 100%;">
        @php
            $meetingCategory = $tW->meetingCategory;
        @endphp
        @isset($meetingCategory)
            <tr style="">
                <td style="width: 15%;text-align: right !important;"> Category : </td>
                <td style=""> {{ $meetingCategory->name }} </td>
            </tr>
        @endisset
        <tr style="">
            <td style="width: 15%;text-align: right !important;"> 3W : </td>
            <td style=""> {{ $tW->title }} </td>
        </tr>
        <tr style="">
            <td style="width: 15%;text-align: right !important;"> Information : </td>
            <td style=""> {{ $tWInfo->description }} </td>
        </tr>
        <tr style="">
            <td style="width: 15%;text-align: right !important;"> Raised By : </td>
            <td style=""> {{ $tWInfo->created_user }} </td>
        </tr>
    </table>

    <p>Click the following link to view 3W <a href="{!! route('tw.show', $tW->id) !!}"> Link </a></p>
    <p>Click the following link to view 3W information <a href="{!! route('twInfo.show', $tWInfo->id) !!}"> Link </a></p>
@endisset

<p>****** System Genarated Message ******</p>