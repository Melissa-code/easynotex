import NotesView from "./views/NotesView.vue";
import NoteView from "./views/NoteView.vue";
import CreateNoteView from "./views/CreateNoteView.vue";

export const routes = [
  {path: "/", component: NotesView}, 
  {path: "/note/:id", component: NoteView}, 
  {path: "/note/create", component: CreateNoteView}, 
]