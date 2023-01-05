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

  get breadcrumbs(): BehaviorSubject<any[]> {
    return this._breadcrumbs;
  }

  set breadcrumbs(value: BehaviorSubject<any[]>) {
    this._breadcrumbs = value;
  }

  hide() {
    this.breadcrumbs.next(null);
  }

  public replaceLast(breadcrumbReplace: IBreadcrumb) {
    const b = <IBreadcrumb[]>this.breadcrumbs.getValue();
    b.splice(-1, 1);
    b.push(breadcrumbReplace);
    this.breadcrumbs.next(b);
  }

  public deleteIndex(index: number) {
    const b = <IBreadcrumb[]>this.breadcrumbs.getValue();
    b.splice(index, 1);
    this.breadcrumbs.next(b);
  }

  public getLast(): IBreadcrumb {
    const b = <IBreadcrumb[]>this.breadcrumbs.getValue();
    return b[b.length - 1];
  }

  public getByIndex(index: number) {
    const b = <IBreadcrumb[]>this.breadcrumbs.getValue();
    return b[index];
  }

  public replaceByIndex(breadcrumbReplace: IBreadcrumb, index: number) {
    const b = <IBreadcrumb[]>this.breadcrumbs.getValue();
    b[index] = (breadcrumbReplace);
    this.breadcrumbs.next(b);
  }

  public addByIndex(breadcrumbReplace: IBreadcrumb, index: number) {
    const b = <IBreadcrumb[]>this.breadcrumbs.getValue();
    b.splice(index, 0, breadcrumbReplace);
    this.breadcrumbs.next(b);
  }

  public replaceLabelByIndex(label: string, index: number) {
    if (label.trim().length > 0) {
      const b = <IBreadcrumb[]>this.breadcrumbs.getValue();
      if (b.hasOwnProperty(index)) {
        b[index].label = label;
        this.breadcrumbs.next(b);
      }
    }
  }

  private getBreadcrumbs(route: ActivatedRoute, url: string = '', breadcrumbs: IBreadcrumb[] = [], uniqPath = []): IBreadcrumb[] {
    const ROUTE_DATA_BREADCRUMB = 'breadcrumb';

    // get the child routes
    const children: ActivatedRoute[] = route.children;

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
