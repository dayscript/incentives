<div class="card">
  <div class="table-responsive">
    <table class="table text-nowrap">
      <thead>
        <tr>
          <th style="width: 50px">ID</th>
          <th style="width: 300px;">NOMBRE</th>
          <th>DESCRIPTION</th>
          <th>TYPE</th>
          <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
        </tr>
      </thead>
      <tbody>
        <tr class="table-active table-border-double">
          <td colspan="3">Entidades activas</td>
          <td class="text-right">
            <span class="badge bg-blue badge-pill">24</span>
          </td>
        </tr>
        @foreach($entities as $entity)
        <tr>
          <td class="text-center">
            <h6 class="mb-0">{{ $entity->id }}</h6>
            <div class="font-size-sm text-muted line-height-1">ID</div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <div class="mr-4">
                <a href="#" class="btn bg-teal-400 rounded-round btn-icon btn-sm">
                  <span class="letter-icon--">{{ $entity->getPoints() }}</span>
                </a>
              </div>
              <div>
                <a href="#" class="text-default font-weight-semibold letter-icon-title">{{$entity->name}}</a>
                <div class="text-muted font-size-sm">
                  <span class="badge badge-mark border-blue mr-1"></span>
                  <span class="badge bg-blue"> Active </span>
                </div>
              </div>
            </div>
          </td>
          <td>
            <a href="#" class="text-default">
              <div class="font-weight-semibold">{{ $entity->identification }}</div>
              <span class="text-muted">{{$entity->created_at}}</span>
            </a>
          </td>
          <td>
            <div class="d-flex align-items-center">
              {{$entity->type->name}}
            </div>
          </td>
          <td class="text-center">
            <div class="list-icons">
              <div class="list-icons-item dropdown">
                <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></a>
                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: -152px;; transform: translate3d(16px, 16px, 0px);">
                  <ul>
                  <li><a href="/api/entities/{{ $entity->identification }}" class="dropdown-item"><i class="icon-undo"></i> Resumen JSON</a></li>
                  <li><a href="/entities/{{ $entity->id }}/edit" class="dropdown-item"><i class="icon-history"></i> Editar</a></li>
                    <div class="dropdown-divider"></div>
                  <li><a href="#" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Eliminar </a></li>
                  <li><a href="#" class="dropdown-item"><i class="icon-checkmark3 text-success"></i>Bloquear</a></li>
                  </ul>

                </div>
              </div>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
