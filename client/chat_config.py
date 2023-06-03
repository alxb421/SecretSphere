import tkinter as tk
from tkinter import ttk, messagebox, filedialog
import json
from tkinter.colorchooser import askcolor
import os

class ConfigGeneratorGUI:
    def __init__(self):
        self.window = tk.Tk()
        self.window.title("Config Generator")
        self.window.geometry("400x400")

        self.nickname_entry = None
        self.nickname_color_button = None
        self.get_messages_entry = None
        self.send_messages_entry = None
        self.delete_messages_entry = None
        self.buzz_button_entry = None
        self.get_messages_title_entry = None
        self.next_update_delay_entry = None
        self.chat_tabs_entries = []

        self.create_gui()
        self.load_config()

        self.window.mainloop()

    def create_gui(self):
        frame = tk.Frame(self.window, padx=10, pady=10)
        frame.pack(fill=tk.BOTH, expand=True)

        nickname_label = tk.Label(frame, text="Nickname:")
        nickname_label.grid(row=0, column=0, sticky="w")

        self.nickname_entry = tk.Entry(frame)
        self.nickname_entry.grid(row=0, column=1, pady=5, padx=5)

        self.nickname_color_button = tk.Button(frame, text="Choose Color", command=self.choose_nickname_color)
        self.nickname_color_button.grid(row=0, column=2, padx=5)

        send_messages_label = tk.Label(frame, text="Send Messages Location:")
        send_messages_label.grid(row=2, column=0, sticky="w")

        self.send_messages_entry = tk.Entry(frame)
        self.send_messages_entry.grid(row=2, column=1, pady=5, padx=5)

        next_update_delay_label = tk.Label(frame, text="Next Update Delay (ms):")
        next_update_delay_label.grid(row=6, column=0, sticky="w")

        self.next_update_delay_entry = tk.Entry(frame)
        self.next_update_delay_entry.grid(row=6, column=1, pady=5, padx=5)

        save_button = tk.Button(self.window, text="Save Config", command=self.save_config, width=12)
        save_button.pack(pady=10)

        load_button = tk.Button(self.window, text="Load Config", command=self.load_config_dialog, width=12)
        load_button.pack(pady=5)

    def choose_nickname_color(self):
        color = askcolor()
        if color[1]:
            color_code = color[1]
            self.nickname_color_button.configure(bg=color_code)

    def add_tab_entry(self):
        tab_frame = tk.Frame(self.window, padx=10, pady=5)
        tab_frame.pack(fill=tk.BOTH, expand=True)

        title_label = tk.Label(tab_frame, text="Tab Title:")
        title_label.grid(row=0, column=0, sticky="w")

        title_entry = tk.Entry(tab_frame)
        title_entry.grid(row=0, column=1, pady=5, padx=5)
        self.chat_tabs_entries.append(self.send_messages_entry.get())

    def load_config(self):
        if os.path.isfile("config.json"):
            with open("config.json", "r") as file:
                config = json.load(file)

            self.nickname_entry.insert(0, config.get("nickname", ""))
            self.send_messages_entry.insert(0, config.get("send_messages_location", ""))
            self.next_update_delay_entry.insert(0, config.get("next_update_delay", ""))

    def load_config_dialog(self):
        file_path = filedialog.askopenfilename(filetypes=[("JSON Files", "*.json")])
        if file_path:
            with open(file_path, "r") as file:
                config = json.load(file)

            self.nickname_entry.delete(0, tk.END)
            self.send_messages_entry.delete(0, tk.END)
            self.next_update_delay_entry.delete(0, tk.END)

            self.nickname_entry.insert(0, config.get("nickname", ""))
            self.send_messages_entry.insert(0, config.get("send_messages_location", ""))
            self.next_update_delay_entry.insert(0, config.get("next_update_delay", ""))

    def save_config(self):
        self.add_tab_entry()
        nickname = self.nickname_entry.get()
        send_messages_location = self.send_messages_entry.get()
        next_update_delay = self.next_update_delay_entry.get()

        chat_tabs = []
        for entry in self.chat_tabs_entries:
            title = send_messages_location
            chat_tabs.append({
                "title": send_messages_location,
                "get_messages_location": send_messages_location + "/get_messages.php",
                "send_messages_location": send_messages_location + "/index.php",
            })

        config = {
            "nickname": f"<span style='color: {self.nickname_color_button['bg']}'><b>{nickname}</b></span>",
            "get_messages_location": send_messages_location + "/get_messages.php",
            "send_messages_location": send_messages_location + "/index.php",
            "delete_messages_location": send_messages_location + "/delete_messages.php",
            "buzz_button_location": send_messages_location + "/buzz.php",
            "join_status_location": send_messages_location + "/join.php",
            "leave_status_location": send_messages_location + "/leave.php",
            "ban_location": send_messages_location + "/ban.php",
            "next_update_delay": int(next_update_delay),
            "chat_tabs": chat_tabs
        }

        with open("config.json", "w") as file:
            json.dump(config, file, indent=4)

        messagebox.showinfo("Success", "Configurations saved successfully.")

# Run the application
if __name__ == "__main__":
    config_generator = ConfigGeneratorGUI()
