import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {MainBreadcrumbsComponent} from './main-breadcrumbs.component';

describe('MainBreadcrumbsComponent', () => {
  let component: MainBreadcrumbsComponent;
  let fixture: ComponentFixture<MainBreadcrumbsComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [MainBreadcrumbsComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MainBreadcrumbsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
