import tkinter as tk
from tkinter import ttk, messagebox, Menu, filedialog
import requests
import json
from tkhtmlview import HTMLLabel
import os, winsound

class ChatAppGUI:
    def __init__(self):
        self.window = tk.Tk()


##############
        def donothing(self):
            filewin = Toplevel(sel.window)
            button = Button(filewin, text="Do nothing button")
            button.pack()
#############

        menubar = Menu(self.window)
        filemenu = Menu(menubar, tearoff=0)
        filemenu.add_command(label="Delete chat immediately", command=self.delete_messages)
        filemenu.add_command(label="Buzz", command=self.send_buzz)
        filemenu.add_separator()
        filemenu.add_command(label="Exit", command=self.window.quit)
        menubar.add_cascade(label="Conversation", menu=filemenu)

        editmenu = Menu(menubar, tearoff=0)

        editmenu.add_command(label="Room setup", command=self.goto_room_settings)
        editmenu.add_separator()
        editmenu.add_command(label="Encrypt chat", command=donothing, state="disabled")
        menubar.add_cascade(label="Options", menu=editmenu)
        helpmenu = Menu(menubar, tearoff=0)


        menubar.add_cascade(label="Send To", menu=helpmenu, state="disabled")
        self.window.config(menu=menubar)

        self.window.title("SecretSphere")
        self.window.geometry("400x500")
        self.window.columnconfigure(0, weight=1)
        self.window.rowconfigure(1, weight=1)

        self.config = {}
        self.chat_tabs = []

        self.load_config()
        self.create_gui()
        self.update_chat_views()

        self.window.protocol("WM_DELETE_WINDOW", self.on_window_close)
        self.window.mainloop()

    def create_gui(self):
        tab_control = ttk.Notebook(self.window)
        tab_control.pack(fill=tk.BOTH, expand=True)

        for tab in self.config.get('chat_tabs', []):
            chat_tab = ttk.Frame(tab_control)
            tab_control.add(chat_tab, text=tab.get('title', ''))
            self.create_chat_view(chat_tab, tab.get('get_messages_location', ''))

        button_frame = tk.Frame(self.window)
        button_frame.pack(fill=tk.X, padx=5, pady=0)

        font_icon = tk.PhotoImage(file="../assets/font_icon.png")  # Replace "font_icon.png" with the path to your font icon image
        font_icon_resized = font_icon.subsample(20, 20)  # Adjust the subsampling factor as needed
        font_button = ttk.Button(button_frame, text="Font", width=4,image=font_icon_resized, compound=tk.LEFT, command=self.change_font)
        font_button.image = font_icon_resized  # Save a reference to the resized image to prevent garbage collection
        font_button.pack(side=tk.LEFT, padx=0, pady=0)
        font_button.configure(style="Small.TButton")



        # Insert button with icon
        insert_icon = tk.PhotoImage(file="../assets/insert_icon.png")  # Replace "insert_icon.png" with the path to your insert icon image
        insert_icon_resized = insert_icon.subsample(20, 20)  # Adjust the subsampling factor as needed
        insert_button = ttk.Button(button_frame, text="Insert", width=4, image=insert_icon_resized,
                                   compound=tk.LEFT, command=self.insert_file)
        insert_button.image = insert_icon_resized  # Save a reference to the resized image to prevent garbage collection
        insert_button.pack(side=tk.LEFT, padx=0, pady=0)
        insert_button.configure(style="Small.TButton")


        # Smile button with icon
        smile_icon = tk.PhotoImage(file="../assets/smile_icon.png")
        smile_icon_resized = smile_icon.subsample(30, 30)
        smile_button = ttk.Button(button_frame, text="Smile", width=4, image=smile_icon_resized,
                                  compound=tk.LEFT, command=self.insert_smile)
        smile_button.image = smile_icon_resized
        smile_button.pack(side=tk.LEFT, padx=0, pady=0)
        smile_button.configure(style="Small.TButton")


        # Buzz button with icon
        buzz_icon = tk.PhotoImage(file="../assets/buzz_icon.png")  # Replace "buzz_icon.png" with the path to your buzz icon image
        buzz_icon_resized = buzz_icon.subsample(32, 32)  # Adjust the subsampling factor as needed
        buzz_button = ttk.Button(button_frame, text="Buzz", width=4, image=buzz_icon_resized, compound=tk.LEFT, command=self.send_buzz)
        buzz_button.image = buzz_icon_resized  # Save a reference to the resized image to prevent garbage collection
        buzz_button.pack(side=tk.LEFT, padx=0, pady=0)
        buzz_button.configure(style="Small.TButton")
        style = ttk.Style()
        style.configure("Small.TButton",
                        font=("Arial", 10),
                        padding=3)


        message_frame = tk.Frame(self.window)
        message_frame.pack(fill=tk.X, padx=5, pady=5)

        self.message_entry = tk.Entry(message_frame, font=("Arial", 10))
        self.message_entry.pack(fill=tk.X, padx=0, pady=0, ipady=15)
        self.message_entry.bind("<Return>", self.send_message)

    def insert_file(self):
        file_path = filedialog.askopenfilename()
        if file_path:
            self.selected_file = file_path
            messagebox.showinfo("Insert File", f"Insert file: {file_path}")
            self.send_message_with_file()

    def send_message_with_file(self):
        message = self.message_entry.get()
        if message:
            if self.selected_file:
                try:
                    with open(self.selected_file, 'rb') as file:
                        files = {'media': file}
                        data = {'nickname': self.config.get('nickname', ''), 'message': message}

                        response = requests.post(self.config.get('send_messages_location', ''), files=files, data=data)
                        if response.status_code == 200:
                            print("Message with file sent successfully.")
                        else:
                            print("Failed to send the message with file.")

                except IOError:
                    print("Failed to open the selected file.")
            else:
                print("No file selected.")

            self.message_entry.delete(0, tk.END)
            self.selected_file = None


    def insert_smile(self):
        smile_text = ":)))"
        current_text = self.message_entry.get()
        new_text = current_text + smile_text
        self.message_entry.delete(0, tk.END)
        self.message_entry.insert(0, new_text)

    def goto_room_settings(self):
        os.system("python3 ./chat_config.py")

    def create_chat_view(self, parent, messages_location):
        chat_frame = tk.Frame(parent)
        chat_frame.pack(fill=tk.BOTH, expand=False)

        chat_view = HTMLLabel(chat_frame, bg="#FFFFFF", html="", font=("Arial", 8))
        chat_view.pack(fill=tk.BOTH, expand=True, pady=10, ipady=0)

        scrollbar = tk.Scrollbar(chat_frame, command=chat_view.yview)
        scrollbar.pack(side=tk.RIGHT, fill=tk.Y)
        chat_view.config(yscrollcommand=scrollbar.set)

        self.chat_tabs.append({
            'chat_view': chat_view,
            'messages_location': messages_location
        })

    def send_message(self, event=None):
        message = self.message_entry.get()

        if message.startswith("/"):
            self.process_command(message)
        else:
            response = requests.post(self.config.get('send_messages_location', ''), data={'nickname': self.config.get('nickname', ''), 'message': message})
            if response.status_code != 200:
                print("Failed to send the message.")

            self.message_entry.delete(0, tk.END)

    def process_command(self, command):
        message = self.message_entry.get()
        if command == "/delete":
            self.delete_messages()
        elif command == "/buzz":
            self.send_buzz()
        elif "/ban" in command:
            self.ban_user()
        elif command == "/exit":
            self.window.destroy()
        else:
            messagebox.showinfo("Info", "Unknown command: {}".format(command))

    def ban_user(self):
        message = self.message_entry.get()
        message = message[5:] + "\n"
        print(message + " has been banned")
        response = requests.post(self.config.get('ban_location', ''), data={'message': message})

    def delete_messages(self):
            response = requests.post(self.config['delete_messages_location'])
            if response.status_code == 200:
                print("deleted messages")
            else:
                messagebox.showerror("Error", "Failed to delete messages.")

    def user_join(self):
            response = requests.post(self.config.get('join_status_location', ''), data={'nickname': self.config.get('nickname', '')})
            if response.status_code == 200:
                print("User join")
            else:
                messagebox.showerror("Error", "User could't join.")

    def user_leave(self):
            response = requests.post(self.config.get('leave_status_location', ''), data={'nickname': self.config.get('nickname', '')})
            if response.status_code == 200:
                print("User leave")
            else:
                messagebox.showerror("Error", "User could't leave.")

    def send_buzz(self):
        response = requests.post(self.config.get('buzz_button_location', ''))
        if response.status_code != 200:
            print("Failed to send buzz.")

    def update_chat_views(self):
        for tab in self.chat_tabs:
            chat_view = tab['chat_view']
            messages_location = tab['messages_location']

            response = requests.get(messages_location)
            if response.status_code == 200:
                messages = response.text
                chat_view.set_html(messages)
                chat_view.yview(tk.END)
            else:
                print("Failed to retrieve chat messages.")

        self.window.after(self.config.get('next_update_delay', 1000), self.update_chat_views)

    def load_config(self):
        try:
            with open('config.json', 'r') as file:
                self.config = json.load(file)
                self.window.title(self.config.get('title', 'SecretSphere'))
                self.window.geometry(self.config.get('geometry', '800x600'))

                #response = requests.post(self.config.get('delete_messages_location', ''))
                self.user_join()

        except FileNotFoundError:
            os.system("python3 ./chat_config.py")
            self.window.quit

    def on_window_close(self):
        #response = requests.post(self.config.get('delete_messages_location', ''))
        self.user_leave()
        self.window.destroy()

    def change_font(self):
        # Implement the font change functionality
        pass

    def insert_text(self):
        # Implement the text insertion functionality
        pass

    def insert_smile(self):
        self.message_entry.insert(tk.END, ":)))")
        

if __name__ == '__main__':
    chat_app = ChatAppGUI()
