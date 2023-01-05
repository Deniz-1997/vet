import {Component, Inject, Injectable, OnDestroy, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {ReferenceOwnerStatusModel} from '../../../../../../models/reference/reference.owner.status.models';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {BehaviorSubject, Observable, of as ofObservable, Subject} from 'rxjs';
import {takeUntil} from 'rxjs/operators';
import {RoleModel} from '../../../../../../models/role.models';
import {CrudType} from '../../../../../../common/crud-types';
import {MatTreeFlatDataSource, MatTreeFlattener} from '@angular/material/tree';
import {FlatTreeControl} from '@angular/cdk/tree';
import {SelectionModel} from '@angular/cdk/collections';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {getCrudModelCreateLoading, getCrudModelData} from '../../../../../../api/api-connector/crud/crud.selectors';
import {AuthState} from '../../../../../../api/auth/auth.reducer';
import {CrudState} from '../../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadMatchesAction} from '../../../../../../api/api-connector/crud/crud.actions';
import {CrudDataType} from '../../../../../../api/api-connector/crud/crud.config';

/**
 * Node for to-do item
 */
export class TodoItemNode {
  children: Array<TodoItemNode>;
  item: string;
  roles: null | Array<{ id: number; name: string; }>;
}

/** Flat to-do item node with expandable and level information */
export class TodoItemFlatNode {
  item: string;
  roles: null | Array<{ id: number; name: string; }>;
  level: number;
  expandable: boolean;
}


@Injectable()
export class ChecklistDatabase {
  dataChange: BehaviorSubject<Array<TodoItemNode>> = new BehaviorSubject<Array<TodoItemNode>>([]);
  roles: Array<{ id: number; name: string; code: string; }> = [];

  constructor(
    private store: Store<AuthState>
  ) {

  }

  get data(): Array<TodoItemNode> {
    return this.dataChange.value;
  }

  initialize(roles?: Array<{ id: number; name: string; code: string; }>): void {
    if (roles) {
      this.roles = roles;
    }
    this.store.pipe(select(getCrudModelData, {type: CrudType.Action}));
    this.store.dispatch(new LoadMatchesAction({
      type: CrudType.Action,
      params: {
        filter: {
          groups: {code: 'LEFT_MENU'},
          parent: 'isNull'
        },
        order: {sort: 'ASC', items: {sort: 'ASC'}},
        fields: {
          0: 'id',
          1: 'name',
          2: 'roles',
          items: ['id', 'name', 'roles'],
          roles: ['id', 'name', 'code']
        }
      } as any,

      onSuccess: response => {
        if (response.response) {
          const data = this.buildFileTree(response.response.items, 0);
          // Notify the change.
          this.dataChange.next(data);
        }
      }
    }));


  }

  getRoles(roles: any): Array<{ id: number; name: string; code?: string }> {
    if (!this.roles || !roles) {
      return roles;
    }
    const result = [];

    for (const i in roles) {
      if (roles.hasOwnProperty(i)) {
        for (const j in this.roles) {
          if (this.roles.hasOwnProperty(j)) {
            if (roles[i].id === this.roles[j].id) {
              result.push(new RoleModel(this.roles[j] as RoleModel));
              break;
            }
          }
        }
      }
    }

    return result;
  }

  /**
   * Build the file structure tree. The `value` is the Json object, or a sub-tree of a Json object.
   * The return value is the list of `TodoItemNode`.
   */
  buildFileTree(value: any, level: number): Array<any> {
    const data: Array<any> = [];
    for (const k in value) {
      if (value.hasOwnProperty(k)) {
        const v = value[k];
        const node = new TodoItemNode();
        node.item = `${v.name}`;
        node.roles = this.getRoles(v.roles);
        if (!v.items || v.items.length === 0) {
          // no action
        } else if (typeof v === 'object') {
          node.children = this.buildFileTree(v.items, level + 1);
        } else {
          node.item = v.name;
          node.roles = this.getRoles(v.roles);
        }
        data.push(node);
      }
    }
    return data;
  }
}


@Component({
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.css'],
  providers: [ChecklistDatabase]
})
export class EditComponent extends ReferenceItemModels implements OnDestroy {
  roles$: Observable<Array<CrudDataType>>;
  roles: Array<RoleModel>;
  getLoading$: Observable<boolean>;
  selectedOptions: Array<RoleModel>;
  flatNodeMap: Map<TodoItemFlatNode, TodoItemNode> = new Map<TodoItemFlatNode, TodoItemNode>();
  /** Map from nested node to flattened node. This helps us to keep the same object for selection */
  nestedNodeMap: Map<TodoItemNode, TodoItemFlatNode> = new Map<TodoItemNode, TodoItemFlatNode>();
  treeControl: FlatTreeControl<TodoItemFlatNode>;
  treeFlattener: MatTreeFlattener<TodoItemNode, TodoItemFlatNode>;
  dataSource: MatTreeFlatDataSource<TodoItemNode, TodoItemFlatNode>;
  /** The selection for checklist */
  checklistSelection = new SelectionModel<TodoItemFlatNode>(true /* multiple */);
  protected listNavigate = ['admin', 'group'];
  protected titleName = 'Тип пользователя';
  private destroy$ = new Subject<any>();

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    private database: ChecklistDatabase,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.Group, ReferenceOwnerStatusModel, data?.id, data?.openDialog);

    this.roles$ = this.store.pipe(select(getCrudModelData, {type: CrudType.Role}));
    this.getLoading$ = this.store.pipe(select(getCrudModelCreateLoading));
    this.store.dispatch(new LoadGetListAction({type: CrudType.Role, params: {fields: {0: 'id', 1: 'name', 2: 'code'}}}));

    this.roles$
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe((role: Array<RoleModel>) => {
        this.roles = role;
        if (this.item.roles) {
          this.selectedOptions = role.filter(item => this.item.roles.some(element => element.id === item.id));
        }
        if (this.database.roles.length < 1 && this.roles.length > 0) {
          this.database.initialize(this.roles);
        }
      });


    this.treeFlattener = new MatTreeFlattener(this.transformer, this.getLevel,
      this.isExpandable, this.getChildren);
    this.treeControl = new FlatTreeControl<TodoItemFlatNode>(this.getLevel, this.isExpandable);
    this.dataSource = new MatTreeFlatDataSource(this.treeControl, this.treeFlattener);

    database.dataChange.subscribe(dataChanged => {
      this.dataSource.data = dataChanged;
    });
  }

  onSelectAll(): void {
    this.selectedOptions = this.roles;
    this.formGroup.controls.roles.patchValue(this.roles);
  }

  onSelectAllRead(): void {
    const role: Array<RoleModel> = this.roles.filter((item: RoleModel) => item.code.indexOf('ROLE_READ') === 0);
    const selectedRole: Array<RoleModel> = this.formGroup.controls.roles.value;
    selectedRole.map(
      item => {
        if (!role.some(i => i.id === item.id)) {
          role.push(item);
        }
      }
    );
    this.formGroup.controls.roles.patchValue(role);
  }

  onDeselectAll(): void {
    this.selectedOptions = [];
    this.formGroup.controls.roles.patchValue([]);
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  getLevel = (node: TodoItemFlatNode) => node.level;

  isExpandable = (node: TodoItemFlatNode) => node.expandable;

  getChildren = (node: TodoItemNode): Observable<Array<TodoItemNode>> => ofObservable(node.children);

  hasChild = (_: number, _nodeData: TodoItemFlatNode) => _nodeData.expandable;

  /**
   * Transformer to convert nested node to flat node. Record the nodes in maps for later use.
   */
  transformer = (node: TodoItemNode, level: number) => {
    // tslint:disable-next-line:no-non-null-assertion
    const flatNode = this.nestedNodeMap.has(node) && this.nestedNodeMap.get(node)!.item === node.item ? this.nestedNodeMap.get(node)!
      : new TodoItemFlatNode();
    flatNode.item = node.item;
    flatNode.level = level;
    flatNode.roles = node.roles;
    flatNode.expandable = !!node.children;
    this.flatNodeMap.set(flatNode, node);
    this.nestedNodeMap.set(node, flatNode);
    return flatNode;
  }

  isSelectedRoles(roles: null | Array<{ id: number; name: string; }>, partial: boolean = false): boolean {
    if (!roles || roles.length === 0) {
      return false;
    }

    const descendants = this.formGroup.controls.roles.value;
    if (!descendants || descendants.length === 0) {
      return false;
    }
    let cnt = 0;
    descendants.map(
      item => {
        roles.map(r => {
          if (r.id === item.id) {
            cnt++;
          }
        });
      }
    );
    return !!(cnt && roles.length === cnt || (partial && cnt > 0));
  }

  /** Whether all the descendants of the node are selected */
  descendantsAllSelected(node: TodoItemFlatNode): boolean {
    const descendants = this.treeControl.getDescendants(node);
    return descendants.every(child => !child.roles || this.isSelectedRoles(child.roles))
      && (this.isSelectedRoles(node.roles)
        || !node.roles
        || node.roles.length < 1);
  }

  /** Whether part of the descendants are selected */
  descendantsPartiallySelected(node: TodoItemFlatNode): boolean {
    const descendants = this.treeControl.getDescendants(node);
    const result = descendants.some(child => this.isSelectedRoles(child.roles));
    return result && !this.descendantsAllSelected(node);
  }

  /** Toggle the to-do item selection. Select/deselect all the descendants node */
  todoItemSelectionToggle(node: TodoItemFlatNode): void {
    this.checklistSelection.toggle(node);
    const descendants = this.treeControl.getDescendants(node);
    if (this.checklistSelection.isSelected(node)) {
      this.checklistSelection.select(...descendants);
      descendants.map(item => {
        if (!this.isSelectedRoles(item.roles)) {
          this.toggleSelectedMenu(item);
        }
      });

    } else {
      this.checklistSelection.deselect(...descendants);
      descendants.map(item => {
        if (this.isSelectedRoles(item.roles)) {
          this.toggleSelectedMenu(item);
        }
      });
    }
  }

  isSelectedMenu(node: TodoItemFlatNode): boolean {
    return this.isSelectedRoles(node.roles);
  }

  toggleSelectedMenu(node: TodoItemFlatNode): void {
    if (!node.roles || node.roles.length === 0) {
      return;
    }
    let descendants = this.formGroup.controls.roles.value;
    let cnt = 0;
    let selectedIndex = [];
    let action = 'add';
    if (descendants && descendants.length > 0) {
      descendants.map(
        (item, index) => {
          node.roles.map(r => {
            if (r.id === item.id) {
              cnt++;
              selectedIndex.push(index);
            }
          });
        }
      );
    }


    if (cnt && node.roles.length === cnt) {
      action = 'delete';
      for (let ii = selectedIndex.length - 1; ii >= 0; ii--) {
        descendants.splice(selectedIndex[ii], 1);
      }
    } else {
      for (let ii = selectedIndex.length - 1; ii >= 0; ii--) {
        descendants.splice(selectedIndex[ii], 1);
      }
      if (descendants && descendants.length > 0) {
        descendants = descendants.concat(
          this.roles.filter((item: RoleModel) => node.roles.some(i => i.id === item.id))
        );
      } else {
        descendants = this.roles.filter((item: RoleModel) => node.roles.some(i => i.id === item.id));
      }
    }
    this.selectedOptions = descendants;
    this.formGroup.controls.roles.patchValue(descendants);

    const parent = this.getParent(node);
    if (parent && parent.level < node.level) {
      if (this.descendantsPartiallySelected(parent) && !this.isSelectedRoles(parent.roles)) {
        this.toggleSelectedMenu(parent);
      } else if (!this.descendantsPartiallySelected(parent) && !this.descendantsAllSelected(parent) && this.isSelectedRoles(parent.roles)) {
        this.toggleSelectedMenu(parent);
      } else if (action === 'delete' && this.isSelectedRoles(parent.roles, true)) {
        selectedIndex = [];
        descendants.map(
          (item, index) => {
            parent.roles.map(r => {
              if (r.id === item.id) {
                cnt++;
                selectedIndex.push(index);
              }
            });
          }
        );
        for (let ii = selectedIndex.length - 1; ii >= 0; ii--) {
          descendants.splice(selectedIndex[ii], 1);
        }
        this.selectedOptions = descendants;
        this.formGroup.controls.roles.patchValue(descendants);
      }
    }


  }

  getParent(node: TodoItemFlatNode): any {
    const currentLevel = this.treeControl.getLevel(node);

    if (currentLevel < 1) {
      return null;
    }

    const startIndex = this.treeControl.dataNodes.indexOf(node) - 1;

    for (let i = startIndex; i >= 0; i--) {
      const currentNode = this.treeControl.dataNodes[i];

      if (this.treeControl.getLevel(currentNode) < currentLevel) {
        return currentNode;
      }
    }
  }

  protected setModel(): void {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      code: new FormControl(this.item.code, [Validators.required]),
      roles: new FormControl([]),
    });
    setTimeout(() => {
      if (this.formGroup) {
        this.formGroup.controls.roles.patchValue(this.selectedOptions);
      }
    }, 1000);
  }

}
