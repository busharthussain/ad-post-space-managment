<div class="row m-b-30">
    <div class=" col-5">
        <label for="first_name">{{_lang('Select Company/s')}}</label>
    </div>
    <div class=" col-7">
        <select id="companies" name="companies" class="SlectBox custom-form" multiple>
            @php
                $counter = 0;
                $strCompaniesIndex = '';
            @endphp
            @if(!empty($arrCompanies))
                @foreach($arrCompanies as $key => $row)
                    @php
                        if (!empty($selectedCompanies) && in_array($key,$selectedCompanies))
                        $strCompaniesIndex .= $counter.',';
                        $counter ++;
                    @endphp
                    <option value="{!! $key !!}">{!! $row !!}</option>
                @endforeach
            @endif
            <input type="hidden" name="strCompaniesIndex" id="strCompaniesIndex" value="{!! $strCompaniesIndex !!}">
        </select>
    </div>
</div>
<div class="row m-b-30">
    <div class=" col-5">
        <label for="first_name">{{_lang('Select Communities/s')}}</label>
    </div>
    <div class=" col-7">
        <select id="communities" name="communities" class="SlectBox" multiple>
            @php
                $counter = 0;
                $strCommunitiesIndex = '';
            @endphp
            @if(!empty($arrCommunities))
                @foreach($arrCommunities as $key => $row)
                    @php
                        if (!empty($selectedCommunities) && in_array($key,$selectedCommunities))
                        $strCommunitiesIndex .= $counter.',';
                        $counter ++;
                    @endphp
                    <option value="{!! $key !!}">{!! $row !!}</option>
                @endforeach
            @endif
            <input type="hidden" name="strCommunitiesIndex" id="strCommunitiesIndex" value="{!! $strCommunitiesIndex !!}">
        </select>
    </div>
</div>