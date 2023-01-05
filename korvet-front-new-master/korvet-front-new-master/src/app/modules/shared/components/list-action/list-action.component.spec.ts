import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { ListActionComponent } from './list-action.component';

describe('ListActionComponent', () => {
  let component: ListActionComponent;
  let fixture: ComponentFixture<ListActionComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ ListActionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListActionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
