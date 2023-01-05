import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { ListFilterViewComponent } from './list-filter-view.component';

describe('ListFilterViewComponent', () => {
  let component: ListFilterViewComponent;
  let fixture: ComponentFixture<ListFilterViewComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ ListFilterViewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListFilterViewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
