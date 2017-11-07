

<li class="has-submenu"><a href="#" id="current"><span>{{__('foreigner attendance')}}</span></a>
    <ul class="left-submenu">
        <li class="back"><a href="#">{{__('Back')}}</a></li>

        {{--@can('options_add')--}}
        <li><a href="{{route('')}}" id="current"> <span>{{__('Add attendance')}}</span></a></li>
        {{--@endcan--}}

{{--        @can('options_view')--}}
        <li><a href="{{route('')}}" id="current"> <span>{{__('foreigner attendance')}}</span></a></li>
        {{--@endcan--}}

    </ul>
</li>

