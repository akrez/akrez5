<table class="table table-bordered table-striped table-hover table-responsive align-middle">
    <thead class="table-dark">
        <tr>
            @foreach ($gridTable->columns as $columnKey => $column)
                <th>{!! $gridTable->renderHeader($column, null, null, null, $columnKey) !!}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php($index = 1)
        @foreach ($gridTable->models as $modelKey => $model)
            <tr>
                @foreach ($gridTable->columns as $columnKey => $column)
                    <th {!! $gridTable->renderContentAttributes($column, $model, $index, $modelKey, $columnKey) !!}>
                        {!! $gridTable->renderContent($column, $model, $index, $modelKey, $columnKey) !!}
                    </th>
                @endforeach
            </tr>
            @php($index++)
        @endforeach
    </tbody>
</table>
