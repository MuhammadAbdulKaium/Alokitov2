{{--@if($notices->count()>0)--}}
{{--<ul class="products-list product-list-in-box">--}}
{{--@foreach($notices as $notice)--}}
    {{--<li class="item">--}}
        {{--<div class="fa-stack fa-lg pull-left" aria-hidden="true">--}}
            {{--<i class="fa fa-circle fa-stack-2x img-circle bg-aqua"></i>--}}
            {{--<i class="fa fa-thumb-tack fa-rotate-270 fa-stack-1x text-aqua"></i>--}}
        {{--</div>--}}
        {{--<div class="product-info">--}}
            {{--<a class="product-title" href="/communication/notice/view/{{$notice->id}}" data-target="#globalModal" data-toggle="modal"> {{$notice->title}} <span class="text-muted pull-right"><i class="fa fa-calendar"></i> {{date('F j, Y',strtotime($notice->notice_date))}}</span></a>--}}
            {{--<span class="product-description">{{str_limit($notice->desc,100)}}</span>--}}
        {{--</div>--}}
    {{--</li>--}}
    {{--@endforeach--}}

{{--</ul>--}}
{{--@else--}}
    {{--No Notice Found--}}
    {{--@endif--}}

@if($notices->count()>0)
<table class="table table-striped">
    <tbody>
    @foreach($notices as $notice)
    <tr>
        <td class="notice-heading"><a class="notice-title" href="/communication/notice/view/{{$notice->id}}" data-target="#globalModal" data-toggle="modal">{{$notice->title}} </a> <p>{{\Illuminate\Support\Str::limit($notice->desc, 100)}}</p></td>
        <td class="text-center">{{date('F j, Y',strtotime($notice->notice_date))}}</td>
    </tr>
    @endforeach
    </tbody>
</table>

@else
    No Notice Found
@endif