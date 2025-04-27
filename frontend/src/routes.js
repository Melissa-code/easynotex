import NotesView from "./views/NotesView.vue";
import NoteView from "./views/NoteView.vue";

export const routes = [
  {path: "/", component: NotesView}, 
  {path: "/note/:id", component: NoteView}, 
]