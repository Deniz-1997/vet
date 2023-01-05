import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {Component} from './component';

describe('RowComponent', () => {
  let component: Component;
  let fixture: ComponentFixture<Component>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [Component]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
