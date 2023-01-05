import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListItemIconComponent } from './listItemIcon.component';

describe('ListItemIconComponent', () => {
  let component: ListItemIconComponent;
  let fixture: ComponentFixture<ListItemIconComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ListItemIconComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ListItemIconComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
