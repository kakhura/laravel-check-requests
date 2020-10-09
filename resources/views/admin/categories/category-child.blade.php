<ul class="list-group">
    @foreach ($category->childrenRecursive as $child)
        <li class="list-group-item sort cursor-move" data-id="{{ $child->id }}" data-ordering="{{ $child->ordering }}">
            <div class="for-display">
                <div class="col-md-5 for-display">
                    <span>
                        @for ($i = 0; $i <= $depth; $i++)
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        @endfor
                        @if ($child->image)
                            <img src="{{ asset($child->image) }}" alt="" class="post">
                        @endif
                        {{ $child->id }} : {{ $child->title }}
                    </span>

                    <span class="display-inline-block">
                        <input type="checkbox" id="{{ $child->id }}" class="js-switch publish" {{ $child->published ? 'checked' : '' }}/>
                    </span>
                </div>
                <span>
                    <a href="{{ url('/admin/categories/edit/' . $child->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                    <a href="{{ url('/admin/categories/delete/' . $child->id) }}" class="delete btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a>
                </span>
            </div>
            @if ($child->childrenRecursive->count() > 0)
                @include('vendor.site-bases.admin.categories.category-child' , ['category' => $child, 'depth' => ++$depth])
            @endif
        </li>
    @endforeach
</ul>
