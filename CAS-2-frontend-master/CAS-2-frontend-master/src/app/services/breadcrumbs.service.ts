import {Injectable} from '@angular/core';
import {BehaviorSubject} from 'rxjs';
import {filter} from 'rxjs/operators';
import {ActivatedRoute, NavigationEnd, Params, PRIMARY_OUTLET, Router} from '@angular/router';

export interface IBreadcrumb {
  label: string;
  params: Params;
  url: string;
  current: boolean;
}

@Injectable({
  providedIn: 'root'
})
export class BreadcrumbsService {
  constructor(
    private router: Router,
    private route: ActivatedRoute,
  ) {
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(() => {
        this._breadcrumbs.next(this.getBreadcrumbs(route.root, '/'));
      }
      );
  }

  private _breadcrumbs = new BehaviorSubject([]);

  get breadcrumbs(): BehaviorSubject<Array<any>> {
    return this._breadcrumbs;
  }

  set breadcrumbs(value: BehaviorSubject<Array<any>>) {
    this._breadcrumbs = value;
  }

  hide(): void {
    this.breadcrumbs.next(null);
  }

  public replaceLast(breadcrumbReplace: IBreadcrumb): void {
    const b = this.breadcrumbs.getValue() as Array<IBreadcrumb>;
    b.splice(-1, 1);
    b.push(breadcrumbReplace);
    this.breadcrumbs.next(b);
  }

  public deleteIndex(index: number): void {
    const b = this.breadcrumbs.getValue() as Array<IBreadcrumb>;
    b.splice(index, 1);
    this.breadcrumbs.next(b);
  }

  public getLast(): IBreadcrumb {
    const b = this.breadcrumbs.getValue() as Array<IBreadcrumb>;
    return b[b.length - 1];
  }

  public getByIndex(index: number): IBreadcrumb {
    const b = this.breadcrumbs.getValue() as Array<IBreadcrumb>;
    return b[index];
  }

  public replaceByIndex(breadcrumbReplace: IBreadcrumb, index: number): void {
    const b = this.breadcrumbs.getValue() as Array<IBreadcrumb>;
    b[index] = (breadcrumbReplace);
    this.breadcrumbs.next(b);
  }

  public addByIndex(breadcrumbReplace: IBreadcrumb, index: number): void {
    const b = this.breadcrumbs.getValue() as Array<IBreadcrumb>;
    b.splice(index, 0, breadcrumbReplace);
    this.breadcrumbs.next(b);
  }

  public replaceLabelByIndex(label: string, index: number): void {
    if (label.trim().length > 0) {
      const b = this.breadcrumbs.getValue() as Array<IBreadcrumb>;
      if (b.hasOwnProperty(index)) {
        b[index].label = label;
        this.breadcrumbs.next(b);
      }
    }
  }

  private getBreadcrumbs(route: ActivatedRoute, url: string = '', breadcrumbs: Array<IBreadcrumb> = [], uniqPath: Array<any> = [])
    : Array<IBreadcrumb> {
    const ROUTE_DATA_BREADCRUMB = 'breadcrumb';

    // get the child routes
    const children: Array<ActivatedRoute> = route.children;

    // return if there are no more children
    if (children.length === 0) {
      return breadcrumbs;
    }

    // iterate over each children
    for (const child of children) {
      // verify primary route
      if (child.outlet !== PRIMARY_OUTLET) {
        continue;
      }


      // get the route's URL segment
      const routeURL: string = child.snapshot.url.map(segment => segment.path).join('/').trim();
      // append route URL to URL

      url += (routeURL.length === 0 || (routeURL.substr(1, 1) === '/' || url === '/') ? '' : '/') + `${routeURL}`;
      // verify the custom data property "breadcrumb" is specified on the route
      if (!child.snapshot.data.hasOwnProperty(ROUTE_DATA_BREADCRUMB)) {
        return this.getBreadcrumbs(child, url, breadcrumbs, uniqPath);
      }

      // add breadcrumb
      const breadcrumb: IBreadcrumb = {
        label: child.snapshot.data[ROUTE_DATA_BREADCRUMB],
        params: child.snapshot.params,
        url: url,
        current: url === this.router.url
      };
      // проверяем на уникальность url
      if (uniqPath.includes(url) === false) {
        uniqPath.push(url);
        breadcrumbs.push(breadcrumb);
      }

      // recursive
      return this.getBreadcrumbs(child, url, breadcrumbs, uniqPath);
    }
  }
}
